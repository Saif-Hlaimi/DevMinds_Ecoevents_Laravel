<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::withCount('approvedMembers')->latest()->paginate(12);
        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        return view('groups.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:150'],
            'description' => ['nullable','string','max:2000'],
            'privacy' => ['required','in:public,private'],
            'cover_image' => ['nullable','url'],
            'cover_image_file' => ['nullable','image','max:4096'],
        ]);
        // Basic HTML sanitization: strip tags
        $data['name'] = strip_tags($data['name']);
        if (!empty($data['description'])) {
            $data['description'] = strip_tags($data['description']);
        }
        $data['created_by'] = Auth::id();
        // Handle optional uploaded cover
        $coverPath = null;
        if ($request->hasFile('cover_image_file')) {
            $coverPath = $request->file('cover_image_file')->store('group-covers','public');
        }
        $group = Group::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'privacy' => $data['privacy'],
            'cover_image' => $data['cover_image'] ?? null,
            'cover_image_path' => $coverPath,
            'created_by' => $data['created_by'],
        ]);

        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'role' => 'admin',
            'status' => 'approved',
            'joined_at' => now(),
        ]);

        return redirect()->route('groups.show', $group->slug)->with('success','Group created');
    }

    public function show(string $slug)
    {
        $group = Group::where('slug',$slug)
            ->with(['posts.user','approvedMembers.user'])
            ->firstOrFail();

        $isMember = false;
        if (Auth::check()) {
            $isMember = $group->approvedMembers()->where('user_id', Auth::id())->exists();
        }

        return view('groups.show', compact('group','isMember'));
    }

    public function edit(string $slug)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        abort_unless(auth()->id() === $group->created_by, 403);
        return view('groups.create', compact('group')); // reuse form with old values
    }

    public function update(Request $request, string $slug)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        abort_unless(auth()->id() === $group->created_by, 403);

        $data = $request->validate([
            'name' => ['required','string','max:150'],
            'description' => ['nullable','string','max:2000'],
            'privacy' => ['required','in:public,private'],
            'cover_image' => ['nullable','url'],
            'cover_image_file' => ['nullable','image','max:4096'],
        ]);
        $data['name'] = strip_tags($data['name']);
        if (!empty($data['description'])) {
            $data['description'] = strip_tags($data['description']);
        }
        // Upload new cover if provided
        if ($request->hasFile('cover_image_file')) {
            if ($group->cover_image_path && Storage::disk('public')->exists($group->cover_image_path)) {
                Storage::disk('public')->delete($group->cover_image_path);
            }
            $group->cover_image_path = $request->file('cover_image_file')->store('group-covers','public');
        }
        $group->name = $data['name'];
        $group->description = $data['description'] ?? null;
        $group->privacy = $data['privacy'];
        $group->cover_image = $data['cover_image'] ?? null;
        $group->save();
        return redirect()->route('groups.show',$group->slug)->with('success','Group updated');
    }

    public function destroy(string $slug)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        abort_unless(auth()->id() === $group->created_by, 403);
        $group->delete();
        return redirect()->route('groups.index')->with('success','Group deleted');
    }
}
