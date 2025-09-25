@extends('layouts.app')
@section('title', $group->name)
@section('content')
<section class="page-banner bg-image pt-130 pb-130" data-background="{{ $group->cover_image_src ?: 'https://images.unsplash.com/photo-1453928582365-b6ad33cbcf64?q=80&w=1600&auto=format&fit=crop' }}">
  <div class="container">
    <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">{{ $group->name }}</h2>
    <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
      <a href="{{ route('home') }}">Home :</a>
      <a href="{{ route('groups.index') }}">Groups :</a>
      <span class="primary-color">{{ $group->name }}</span>
    </div>
  </div>
</section>

<section class="pt-130 pb-130">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-8 col-lg-8 order-md-1 order-lg-1">
        @auth
          @if ($isMember)
              <div class="p-4 mb-4" style="border:1px solid #e5e7eb;border-radius:12px;background:#ffffffcc;backdrop-filter:saturate(180%) blur(2px);">
              <form id="create-post-form" action="{{ route('groups.posts.store',$group->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                  <div class="d-flex align-items-center justify-content-between mb-2">
                    <strong>Create a post</strong>
                    <button class="btn btn-success" type="submit" style="min-width:120px;">Publish</button>
                  </div>
                  <textarea name="content" rows="3" class="w-100 form-control @error('content') is-invalid @enderror" placeholder="What's on your mind?"></textarea>
                  <div class="row g-2 mt-2">
                    <div class="col-md-6"><input type="url" name="image_url" class="w-100 form-control @error('image_url') is-invalid @enderror" placeholder="Image URL (optional)"></div>
                    <div class="col-md-6"><input type="file" name="image_file" accept="image/*" class="w-100 form-control @error('image_file') is-invalid @enderror"></div>
                  </div>
              </form>
              </div>
          @endif
        @endauth

        @forelse ($group->posts as $post)
            <div class="p-4 mb-4" data-post-card style="border:1px solid #e5e7eb;border-radius:12px;">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <div>
                <strong>{{ $post->user->name }}</strong>
                <span class="text-muted"> · {{ $post->created_at->diffForHumans() }}</span>
              </div>
              @if (auth()->id() === $group->created_by || auth()->id() === $post->user_id)
                  <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-danger" data-action="delete-post" data-post-id="{{ $post->id }}" style="min-width:72px;">Delete</button>
                  </div>
              @endif
            </div>
            @if ($post->content)
                <p class="mb-2">{{ $post->content }}</p>
            @endif
            @php
              $src = $post->image_src;
            @endphp
            @if ($src)
              <div class="mt-2" style="border-radius:12px;overflow:hidden;">
                <img src="{{ $src }}" alt="post" style="width:100%;height:360px;object-fit:cover;">
              </div>
            @endif
              <div class="d-flex align-items-center gap-3 mt-3 border-top pt-2">
              @auth
                <button type="button" class="btn btn-link p-0" data-action="react" data-type="like" data-post-id="{{ $post->id }}" title="Like">👍 <span class="like-count">{{ $post->reactions->where('type','like')->count() }}</span></button>
                <button type="button" class="btn btn-link p-0" data-action="react" data-type="dislike" data-post-id="{{ $post->id }}" title="Dislike">👎 <span class="dislike-count">{{ $post->reactions->where('type','dislike')->count() }}</span></button>
              @endauth
              @guest
                <span class="text-muted">👍 {{ $post->reactions->where('type','like')->count() }} · 👎 {{ $post->reactions->where('type','dislike')->count() }}</span>
              @endguest
            </div>
              <div class="mt-3">
              <strong>Comments</strong>
              <div class="comments-list" data-comments-list="{{ $post->id }}">
                @foreach ($post->comments as $c)
                  <div class="d-flex align-items-start gap-2 mt-2">
                    <div class="profile-avatar">{{ strtoupper(mb_substr($c->user->name,0,1)) }}</div>
                    <div>
                      <div><strong>{{ $c->user->name }}</strong> <span class="text-muted">· {{ $c->created_at->diffForHumans() }}</span></div>
                      <div>{{ $c->content }}</div>
                    </div>
                  </div>
                @endforeach
              </div>
              @auth
                @if ($isMember)
                  <form method="POST" class="mt-2 comment-form" data-post-id="{{ $post->id }}">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="content" class="w-100 form-control" placeholder="Write a comment..." required>
                        <button type="submit" class="btn btn-success" style="min-width:56px;">➤</button>
                    </div>
                  </form>
                @endif
              @endauth
            </div>
            </div>
        @empty
          <p>No posts yet.</p>
        @endforelse
      </div>
  <div class="col-md-4 col-lg-4 order-md-2 order-lg-2">
        <div class="p-3" style="border:1px solid #e5e7eb;border-radius:12px;">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <div class="text-capitalize">{{ $group->privacy }} group</div>
              <div class="text-muted">Created by {{ $group->creator->name }}</div>
            </div>
            <img class="floaty" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="" style="width:40px;">
          </div>
          <p class="mt-2">{{ $group->description }}</p>
          @auth
            @if (!$isMember)
              <form action="{{ route('groups.join',$group->slug) }}" method="POST">
                @csrf
                <button class="btn-one mt-2" type="submit"><span>{{ $group->privacy === 'public' ? 'Join group' : 'Request to join' }}</span> <i class="fa-solid fa-angles-right"></i></button>
              </form>
            @else
              <form action="{{ route('groups.leave',$group->slug) }}" method="POST">
                @csrf
                <button class="btn-two mt-2" type="submit"><span>Leave group</span></button>
              </form>
            @endif
            @if (auth()->id() === $group->created_by)
              <div class="d-flex gap-2 mt-3">
                <a href="{{ route('groups.edit',$group->slug) }}" class="btn-one"><span>Edit group</span></a>
                <form action="#" method="POST" onsubmit="return confirm('Delete this group?');">
                  @csrf
                  <button class="btn btn-danger" type="button" id="delete-group-btn">Delete group</button>
                </form>
              </div>
            @endif
          @endauth
        </div>

        @php
          $myMembership = auth()->check() ? $group->members()->where('user_id',auth()->id())->first() : null;
        @endphp
        @if ($myMembership && in_array($myMembership->role,['admin','moderator']))
          <div class="p-3 mt-4" style="border:1px solid #e5e7eb;border-radius:12px;">
            <strong>Pending requests</strong>
            @php
              $requests = $group->joinRequests()->where('status','pending')->with('user')->get();
            @endphp
            @forelse ($requests as $r)
              <div class="d-flex align-items-center justify-content-between mt-2">
                <div>{{ $r->user->name }}</div>
                <div class="d-flex gap-2">
                  <form action="{{ route('groups.requests.approve',[$group->slug,$r->id]) }}" method="POST">@csrf<button class="btn-one" type="submit"><span>Approve</span></button></form>
                  <form action="{{ route('groups.requests.reject',[$group->slug,$r->id]) }}" method="POST">@csrf<button class="btn-two" type="submit"><span>Reject</span></button></form>
                </div>
              </div>
            @empty
              <div class="text-muted">No pending requests</div>
            @endforelse
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  (function(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // Real-time validation for create post form
    const postForm = document.getElementById('create-post-form');
    const markInvalid = (el, on) => { if (!el) return; el.classList[on?'add':'remove']('is-invalid'); };
    if (postForm) {
      const contentEl = postForm.querySelector('textarea[name="content"]');
      const urlEl = postForm.querySelector('input[name="image_url"]');
      const fileEl = postForm.querySelector('input[name="image_file"]');
      const urlOk = (v) => !v || /^(https?:)\/\//i.test(v);
      contentEl && contentEl.addEventListener('input', () => markInvalid(contentEl, contentEl.value.length > 5000));
      urlEl && urlEl.addEventListener('input', () => markInvalid(urlEl, !urlOk(urlEl.value)));
      fileEl && fileEl.addEventListener('change', () => {
        const f = fileEl.files && fileEl.files[0];
        markInvalid(fileEl, !!f && !/^image\//.test(f.type));
      });
    }

    // Ajax: create post
    if (postForm) {
      postForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(postForm);
        const res = await fetch(postForm.action, { method:'POST', headers:{ 'X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: formData });
        if (res.ok) { location.reload(); }
      });
    }

    // Ajax: react like/dislike
    document.querySelectorAll('[data-action="react"]').forEach(btn => {
      btn.addEventListener('click', async () => {
        const postId = btn.dataset.postId; const type = btn.dataset.type;
        const res = await fetch(`{{ url('/posts') }}/${postId}/react`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify({ type })});
        if (res.ok) {
          const data = await res.json();
          const card = btn.closest('[data-post-card]') || btn.closest('.p-3');
          if (card) {
            card.querySelector('.like-count').textContent = data.likes;
            card.querySelector('.dislike-count').textContent = data.dislikes;
          }
        }
      });
    });

    // Ajax: comment submit
    document.querySelectorAll('.comment-form').forEach(form => {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const postId = form.dataset.postId; const content = form.querySelector('input[name="content"]').value.trim();
        if (!content) return;
        const res = await fetch(`{{ url('/posts') }}/${postId}/comment`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify({ content })});
        if (res.ok) {
          const data = await res.json();
          const list = document.querySelector(`[data-comments-list="${postId}"]`);
          if (list) {
            const html = `
              <div class="d-flex align-items-start gap-2 mt-2">
                <div class="profile-avatar">${(data.comment.user.name||'?').substring(0,1).toUpperCase()}</div>
                <div>
                  <div><strong>${data.comment.user.name}</strong> <span class="text-muted">· just now</span></div>
                  <div>${data.comment.content}</div>
                </div>
              </div>`;
            list.insertAdjacentHTML('beforeend', html);
          }
          form.reset();
        }
      });
    });

    // Ajax: delete post
    document.querySelectorAll('[data-action="delete-post"]').forEach(btn => {
      btn.addEventListener('click', async () => {
        if (!confirm('Delete this post?')) return;
        const postId = btn.dataset.postId;
        const res = await fetch(`{{ url('/posts') }}/${postId}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN': token, 'Accept':'application/json' } });
        if (res.ok) {
          const card = btn.closest('[data-post-card]') || btn.closest('.p-3');
          if (card) card.remove();
        }
      });
    });

    // Ajax: delete group
    const deleteGroupBtn = document.getElementById('delete-group-btn');
    if (deleteGroupBtn) {
      deleteGroupBtn.addEventListener('click', async () => {
        if (!confirm('Delete this group? This cannot be undone.')) return;
        const res = await fetch(`{{ route('groups.destroy',$group->slug) }}`, { method:'DELETE', headers:{ 'X-CSRF-TOKEN': token, 'Accept':'application/json' } });
        if (res.ok) {
          window.location.href = `{{ route('groups.index') }}`;
        } else {
          // fallback to standard form submission if server expects non-AJAX
          window.location.href = `{{ route('groups.index') }}`;
        }
      });
    }
  })();
</script>
@endpush
