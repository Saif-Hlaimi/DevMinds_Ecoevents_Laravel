<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupAdminController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string)$request->query('q', ''));
        $base = Group::query()
            ->withCount(['approvedMembers as members_count','posts'])
            ->with([
                'creator',
                'posts' => function($q){ $q->latest()->take(5); },
                'posts.user',
            ]);
        if ($search !== '') {
            $base->where('name', 'like', '%'.$search.'%');
        }
        $groups = $base->latest()->paginate(12)->withQueryString();
        return view('admin.groups', [ 'groups' => $groups, 'q' => $search ]);
    }

    public function update(Request $request, Group $group)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'privacy' => 'nullable|in:public,private',
        ]);
        $group->update($data);
        return back()->with('success', 'Group updated');
    }

    public function destroy(Group $group)
    {
        $group->delete();
        return back()->with('success', 'Group deleted');
    }
}
