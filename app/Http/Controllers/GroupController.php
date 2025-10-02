<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        $privacy = $request->query('privacy'); // public|private|null
        $sort = $request->query('sort', 'recent'); // recent|name|members_desc|members_asc|posts_desc

        $query = Group::query()->withCount(['approvedMembers','posts']);
        if ($q !== '') {
            $query->where(function($x) use ($q){
                $x->where('name','like','%'.$q.'%')
                  ->orWhere('description','like','%'.$q.'%');
            });
        }
        if (in_array($privacy, ['public','private'], true)) {
            $query->where('privacy', $privacy);
        }

        switch ($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'members_desc':
                $query->orderByDesc('approved_members_count');
                break;
            case 'members_asc':
                $query->orderBy('approved_members_count');
                break;
            case 'posts_desc':
                $query->orderByDesc('posts_count');
                break;
            case 'recent':
            default:
                $query->latest();
                break;
        }

        $groups = $query->paginate(12)->withQueryString();
        return view('groups.index', [
            'groups' => $groups,
            'q' => $q,
            'privacy' => $privacy,
            'sort' => $sort,
        ]);
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
        // Bad-words moderation: block if name/description contain bad terms
        if (app(\App\Services\ModerationService::class)->hasBadWords(($data['name'] ?? '').' '.($data['description'] ?? ''))) {
            return back()->withErrors(['name' => 'Inappropriate words detected in group details'])->withInput();
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

    public function show(Request $request, string $slug)
    {
        $group = Group::where('slug',$slug)
            ->with(['approvedMembers.user','creator'])
            ->firstOrFail();

        $isMember = false;
        if (Auth::check()) {
            $isMember = $group->approvedMembers()->where('user_id', Auth::id())->exists();
        }

        // Post filters
        $q = trim((string)$request->query('q',''));
        $author = $request->query('author'); // user_id
        $hasImage = $request->boolean('has_image');
        $sort = $request->query('sort','new'); // new|old|liked

        $postsQuery = \App\Models\GroupPost::query()
            ->with(['user','reactions'])
            ->withCount([
                'reactions as likes_count' => function($q){ $q->where('type','like'); },
                'reactions as dislikes_count' => function($q){ $q->where('type','dislike'); },
            ])
            ->where('group_id', $group->id);
        if ($q !== '') {
            $postsQuery->where('content','like','%'.$q.'%');
        }
        if ($author) {
            $postsQuery->where('user_id', $author);
        }
        if ($hasImage) {
            $postsQuery->where(function($x){
                $x->whereNotNull('image_path')->orWhereNotNull('image_url');
            });
        }
        switch ($sort) {
            case 'old':
                $postsQuery->orderBy('created_at','asc');
                break;
            case 'liked':
                $postsQuery->orderByDesc('likes_count')->orderByDesc('created_at');
                break;
            case 'new':
            default:
                $postsQuery->orderBy('created_at','desc');
        }

        $posts = $postsQuery->paginate(10)->withQueryString();

        // Build authors list for filter (users who posted in this group)
        $authorIds = \App\Models\GroupPost::where('group_id',$group->id)->distinct()->pluck('user_id');
        $authors = \App\Models\User::whereIn('id', $authorIds)->orderBy('name')->get(['id','name']);

        return view('groups.show', compact('group','isMember','posts','q','author','hasImage','sort','authors'));
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
