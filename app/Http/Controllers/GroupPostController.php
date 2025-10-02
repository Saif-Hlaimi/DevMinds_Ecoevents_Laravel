<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupPost;
use App\Models\GroupPostComment;
use App\Models\GroupPostReaction;
use App\Notifications\PostCommented;
use App\Notifications\PostReacted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;
use Dompdf\Options;

class GroupPostController extends Controller
{
    private function ensureMember(Group $group)
    {
        $ok = GroupMember::where('group_id',$group->id)
            ->where('user_id',Auth::id())
            ->where('status','approved')->exists();
        abort_unless($ok, 403);
    }

    public function store(Request $request, string $slug)
    {
        $group = Group::where('slug',$slug)->firstOrFail();
        $this->ensureMember($group);

        $data = $request->validate([
            'content' => ['nullable','string','max:5000'],
            'image_url' => ['nullable','url'],
            'image_file' => ['nullable','image','max:4096']
        ]);
        abort_if(empty($data['content']) && empty($data['image_url']) && !$request->hasFile('image_file'), 422, 'Content or image required');

        // Bad-words moderation
        if (!empty($data['content']) && app(\App\Services\ModerationService::class)->hasBadWords($data['content'])) {
            return back()->withErrors(['content' => 'Inappropriate words detected in post content'])->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('image_file')) {
            $imagePath = $request->file('image_file')->store('group-posts','public');
        }

        $post = GroupPost::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'content' => isset($data['content']) ? strip_tags($data['content']) : null,
            'image_url' => $data['image_url'] ?? null,
            'image_path' => $imagePath,
        ]);

        if ($request->expectsJson()) {
            $post->load(['user','comments.user','reactions']);
            return response()->json(['ok'=>true,'post'=>$post]);
        }
        return back()->with('success','Post published');
    }

    public function react(Request $request, int $postId)
    {
        $post = GroupPost::with('group')->findOrFail($postId);
        $this->ensureMember($post->group);
        $data = $request->validate(['type' => ['required','in:like,dislike']]);

        $existing = GroupPostReaction::where('post_id',$postId)->where('user_id',Auth::id())->first();
        if ($existing && $existing->type === $data['type']) {
            $existing->delete(); // toggle off
        } else {
            GroupPostReaction::updateOrCreate(
                ['post_id'=>$postId,'user_id'=>Auth::id()],
                ['type'=>$data['type']]
            );
            if ($post->user_id !== Auth::id()) {
                // Send immediately to the database channel (no queue worker needed)
                optional($post->user)->notifyNow(new PostReacted($post, Auth::user(), $data['type']));
            }
        }
        if ($request->expectsJson()) {
            $post->loadCount(["reactions as likes_count"=>fn($q)=>$q->where('type','like'),"reactions as dislikes_count"=>fn($q)=>$q->where('type','dislike')]);
            return response()->json(['ok'=>true,'likes'=>$post->likes_count,'dislikes'=>$post->dislikes_count]);
        }
        return back();
    }

    public function comment(Request $request, int $postId)
    {
        $post = GroupPost::with('group')->findOrFail($postId);
        $this->ensureMember($post->group);
        $data = $request->validate(['content' => ['required','string','max:2000']]);
        // Bad-words moderation on comments
        if (app(\App\Services\ModerationService::class)->hasBadWords($data['content'] ?? '')) {
            if ($request->expectsJson()) {
                return response()->json(['ok'=>false,'error'=>'Inappropriate words detected'], 422);
            }
            return back()->withErrors(['content' => 'Inappropriate words detected'])->withInput();
        }
        $comment = GroupPostComment::create([
            'post_id'=>$postId,
            'user_id'=>Auth::id(),
            'content'=> strip_tags($data['content'])
        ]);
        if ($post->user_id !== Auth::id()) {
            // Send immediately to the database channel (no queue worker needed)
            optional($post->user)->notifyNow(new PostCommented($post, Auth::user()));
        }
        if ($request->expectsJson()) {
            $comment->load('user');
            return response()->json(['ok'=>true,'comment'=>$comment]);
        }
        return back();
    }

    public function destroy(Request $request, int $postId)
    {
        $post = GroupPost::with('group')->findOrFail($postId);
        // Allow group creator or post owner to delete
        abort_unless(Auth::id() === optional($post->group)->created_by || Auth::id() === $post->user_id, 403);
        // delete stored image if exists
        if ($post->image_path && Storage::disk('public')->exists($post->image_path)) {
            Storage::disk('public')->delete($post->image_path);
        }
        $post->delete();
        if ($request->expectsJson()) {
            return response()->json(['ok'=>true]);
        }
        return back();
    }

    public function pdf(int $postId)
    {
        $post = GroupPost::with(['user','group'])->findOrFail($postId);

        // Access control: if group is private, ensure user is an approved member
        if (optional($post->group)->privacy === 'private') {
            $isMember = GroupMember::where('group_id', $post->group_id)
                ->where('user_id', Auth::id())
                ->where('status', 'approved')
                ->exists();
            abort_unless($isMember, 403);
        }

        // Render Blade to HTML
        $html = view('pdf.post', [
            'post' => $post,
        ])->render();

        // Configure Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true); // allow remote images
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'post-'.$post->id.'-'.now()->format('Ymd_His').'.pdf';
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"'
        ]);
    }
}
