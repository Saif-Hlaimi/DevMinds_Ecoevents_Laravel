<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupJoinRequest;
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
            return back()->with('success','You joined the group');
        }

        // Private: create or keep pending request
        GroupJoinRequest::firstOrCreate(
            ['group_id'=>$group->id,'user_id'=>Auth::id()],
            ['status'=>'pending']
        );
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
        return back()->with('success','Request approved');
    }

    public function reject(Request $request, string $slug, int $requestId)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        $this->authorizeAdmin($group);
        $join = GroupJoinRequest::where('group_id',$group->id)->findOrFail($requestId);
        $join->update(['status'=>'rejected','handled_by'=>Auth::id(),'handled_at'=>now()]);
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
