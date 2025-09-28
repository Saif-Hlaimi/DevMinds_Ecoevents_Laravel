<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupAdminController extends Controller
{
    public function index()
    {
        $groups = Group::withCount(['approvedMembers as members_count','posts'])->latest()->paginate(15);
        return view('admin.groups', compact('groups'));
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
