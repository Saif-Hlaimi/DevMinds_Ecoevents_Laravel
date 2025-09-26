<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupJoinRequest;
use App\Notifications\JoinRequestApproved;
use App\Notifications\JoinRequestCreated;
use App\Notifications\JoinRequestRejected;
use App\Notifications\MemberJoinedPublicGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembershipController extends Controller
{
    public function join(Request $request, string $slug)
    {
        $group = Group::where('slug',$slug)->firstOrFail();

        // Public groups: immediate join
        if ($group->privacy === 'public') {
            GroupMember::firstOrCreate(
                ['group_id'=>$group->id,'user_id'=>Auth::id()],
                ['role'=>'member','status'=>'approved','joined_at'=>now()]
            );
            // Notify group admins/moderators about new member joined
            $admins = GroupMember::where('group_id',$group->id)->whereIn('role',["admin","moderator"])->pluck('user_id');
            foreach ($admins as $adminId) {
                if ($adminId !== Auth::id()) {
                    optional(\App\Models\User::find($adminId))->notifyNow(new MemberJoinedPublicGroup($group, Auth::user()));
                }
            }
            return back()->with('success','You joined the group');
        }

        // Private: create or keep pending request
        GroupJoinRequest::firstOrCreate(
            ['group_id'=>$group->id,'user_id'=>Auth::id()],
            ['status'=>'pending']
        );
        // Notify admins of the group about new join request
        $requestModel = GroupJoinRequest::where('group_id',$group->id)->where('user_id',Auth::id())->latest('id')->first();
        $admins = GroupMember::where('group_id',$group->id)->whereIn('role',["admin","moderator"])->pluck('user_id');
        foreach ($admins as $adminId) {
            if ($adminId !== Auth::id()) {
                optional(\App\Models\User::find($adminId))->notifyNow(new JoinRequestCreated($group, $requestModel));
            }
        }
        return back()->with('success','Request sent to admins');
    }

    public function leave(Request $request, string $slug)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        GroupMember::where('group_id',$group->id)->where('user_id',Auth::id())->delete();
        return back()->with('success','You left the group');
    }

    public function approve(Request $request, string $slug, int $requestId)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        $this->authorizeAdmin($group);
        $join = GroupJoinRequest::where('group_id',$group->id)->findOrFail($requestId);
        $join->update(['status'=>'approved','handled_by'=>Auth::id(),'handled_at'=>now()]);
        GroupMember::firstOrCreate(
            ['group_id'=>$group->id,'user_id'=>$join->user_id],
            ['role'=>'member','status'=>'approved','joined_at'=>now()]
        );
        // Notify requester about approval
        if ($join->user_id !== Auth::id()) {
            optional($join->user)->notifyNow(new JoinRequestApproved($group));
        }
        return back()->with('success','Request approved');
    }

    public function reject(Request $request, string $slug, int $requestId)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        $this->authorizeAdmin($group);
        $join = GroupJoinRequest::where('group_id',$group->id)->findOrFail($requestId);
        $join->update(['status'=>'rejected','handled_by'=>Auth::id(),'handled_at'=>now()]);
        // Notify requester about rejection
        if ($join->user_id !== Auth::id()) {
            optional($join->user)->notifyNow(new JoinRequestRejected($group));
        }
        return back()->with('success','Request rejected');
    }

    private function authorizeAdmin(Group $group): void
    {
        $isAdmin = GroupMember::where('group_id',$group->id)
            ->where('user_id',Auth::id())
            ->whereIn('role',['admin','moderator'])
            ->exists();
        abort_unless($isAdmin, 403);
    }
}
