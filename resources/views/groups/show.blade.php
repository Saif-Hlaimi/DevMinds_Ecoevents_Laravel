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
                  <div class="form-text" id="mod-status-post"></div>
                  <div class="row g-2 mt-2">
                    <div class="col-md-6"><input type="url" name="image_url" class="w-100 form-control @error('image_url') is-invalid @enderror" placeholder="Image URL (optional)"></div>
                    <div class="col-md-6"><input type="file" name="image_file" accept="image/*" class="w-100 form-control @error('image_file') is-invalid @enderror"></div>
                  </div>
                  <div class="d-flex align-items-center gap-2 mt-2">
                    <button type="button" class="btn btn-outline-primary btn-sm" data-inspire-post>✨ Inspire</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="stt-btn" title="Dictate your post">🎙️ Dictate</button>
                    <select class="form-select form-select-sm" id="stt-lang" style="width:auto;">
                      <option value="auto" selected>Auto</option>
                      <option value="en">English</option>
                      <option value="fr">Français</option>
                      <option value="ar">العربية</option>
                    </select>
                    <div class="small text-muted" data-inspire-topics-post>
                      <span class="badge bg-light text-dark me-1" data-topic>Waste Reduction</span>
                      <span class="badge bg-light text-dark me-1" data-topic>Local Cleanup</span>
                      <span class="badge bg-light text-dark me-1" data-topic>Recycling Tips</span>
                      <span class="badge bg-light text-dark me-1" data-topic>Plant a Tree</span>
                    </div>
                  </div>
                  <div class="form-text" id="stt-status"></div>
                  <div class="input-group mt-2" data-inspire-ask-post>
                    <input type="text" class="form-control" placeholder="Ask the assistant: e.g., write a short post inviting people to Saturday cleanup">
                    <button class="btn btn-outline-secondary" type="button">Ask</button>
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
                <button type="button" class="btn btn-sm btn-outline-primary" data-action="tts" data-post-id="{{ $post->id }}">🔊 Read aloud</button>
              @endauth
              <a class="btn btn-sm btn-outline-secondary" href="{{ route('groups.posts.pdf', $post->id) }}" target="_blank">🖨️ Print as PDF</a>
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
                        <button type="button" class="btn btn-outline-secondary" data-action="inspire" data-post-id="{{ $post->id }}">✨ Inspire</button>
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
        const status = document.getElementById('mod-status-post');
        const submitBtn = postForm.querySelector('button[type="submit"]');
        const setStatus = (msg, bad=false, loading=false) => {
          if (!status) return;
          status.textContent = msg || '';
          status.style.color = loading ? '#b91c1c' : (bad ? '#b91c1c' : '#6b7280');
        };
        // moderation pre-check
        try {
          const txt = (postForm.querySelector('textarea[name="content"]')?.value||'').trim();
          if (txt){
            setStatus('Checking for inappropriate language…', false, true);
            submitBtn && (submitBtn.disabled = true);
            const mod = await fetch(`{{ route('api.moderate') }}`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify({ text: txt })});
            if (mod.ok){ const m = await mod.json(); if (m.bad){ setStatus('Bad content detected. Please revise your post.', true); submitBtn && (submitBtn.disabled = false); return; } }
            setStatus('No bad content detected. Posting…');
          }
        } catch(_){ submitBtn && (submitBtn.disabled = false); }
        const res = await fetch(postForm.action, { method:'POST', headers:{ 'X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: formData });
        if (res.ok) { location.reload(); }
      });
    }

    // Speech-to-Text (dictation)
    // Prefer Web Speech API for true real-time interim results; fallback to MediaRecorder + backend chunks
    (function(){
      const dictateBtn = document.getElementById('stt-btn');
      if (!dictateBtn || !postForm) return;
      const statusEl = document.getElementById('stt-status');
      const contentEl = postForm.querySelector('textarea[name="content"]');
      const langEl = document.getElementById('stt-lang');
      const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
      let recognition = null;
      let mediaStream = null;
      let recorder = null;
      let chunks = [];
      let recording = false;
      let intervalId = null;
      let baseText = '';
      const setStatus = (msg, bad=false) => { if (statusEl){ statusEl.textContent = msg||''; statusEl.style.color = bad? '#b91c1c':'#6b7280'; }};
      const updateBtn = () => { dictateBtn.textContent = recording ? '⏹ Stop dictating' : '🎙️ Dictate'; };
      const mapLang = (val) => {
        if (val === 'en') return 'en-US';
        if (val === 'fr') return 'fr-FR';
        if (val === 'ar') return 'ar-SA';
        return navigator.language || 'en-US';
      };

      dictateBtn.addEventListener('click', async () => {
        try {
          if (!recording) {
            // start
            baseText = (contentEl.value || '').trim();
            if (SpeechRecognition) {
              // True real-time via Web Speech API
              recognition = new SpeechRecognition();
              recognition.lang = mapLang(langEl?.value||'auto');
              recognition.interimResults = true;
              recognition.continuous = true;
              let finalTranscript = '';
              recognition.onresult = (event) => {
                let interim = '';
                for (let i = event.resultIndex; i < event.results.length; i++) {
                  const res = event.results[i];
                  if (res.isFinal) finalTranscript += res[0].transcript + ' ';
                  else interim += res[0].transcript;
                }
                contentEl.value = (baseText ? baseText + ' ' : '') + finalTranscript + interim;
              };
              recognition.onerror = (e) => setStatus('Speech recognition error: ' + e.error, true);
              recognition.onend = () => { recording = false; updateBtn(); setStatus('Stopped.'); };
              recognition.start();
              recording = true; updateBtn(); setStatus('Listening… Speak now.');
            } else {
              // Fallback to MediaRecorder + backend (near real-time)
              if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia){
                setStatus('Microphone not supported in this browser.', true); return;
              }
              mediaStream = await navigator.mediaDevices.getUserMedia({ audio: true });
              chunks = [];
              const mimeType = MediaRecorder.isTypeSupported('audio/webm') ? 'audio/webm' : (MediaRecorder.isTypeSupported('audio/mp4') ? 'audio/mp4' : 'audio/webm');
              recorder = new MediaRecorder(mediaStream, { mimeType });
              recorder.ondataavailable = (e) => { if (e.data && e.data.size > 0) chunks.push(e.data); };
              recorder.onstop = async () => {
                try {
                  clearInterval(intervalId); intervalId = null;
                  const blob = new Blob(chunks, { type: mimeType });
                  const b64 = await new Promise(res=>{ const r = new FileReader(); r.onloadend = ()=>res(r.result); r.readAsDataURL(blob); });
                  setStatus('Transcribing final…');
                  const res = await fetch(`{{ route('api.groups.stt', $group->slug) }}`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify({ audio: b64, mime: mimeType, language: langEl?.value||'auto' })});
                  if (res.ok) {
                    const data = await res.json();
                    if (data.ok && data.text) {
                      contentEl.value = (contentEl.value ? contentEl.value + ' ' : '') + data.text;
                      setStatus('Transcription complete.');
                    } else {
                      setStatus('Transcription failed: ' + (data.error||'unknown'), true);
                    }
                  } else {
                    setStatus('Transcription request failed.', true);
                  }
                } catch (err) { setStatus('Transcription error.', true); }
                finally {
                  if (mediaStream) mediaStream.getTracks().forEach(t=>t.stop());
                  recording = false; updateBtn();
                }
              };
              recorder.start();
              // Send partial chunks every 3s
              intervalId = setInterval(async () => {
                try {
                  if (!chunks.length) return;
                  const part = chunks.splice(0, chunks.length);
                  const blob = new Blob(part, { type: recorder.mimeType });
                  const b64 = await new Promise(res=>{ const r = new FileReader(); r.onloadend = ()=>res(r.result); r.readAsDataURL(blob); });
                  setStatus('Transcribing…');
                  const res = await fetch(`{{ route('api.groups.stt', $group->slug) }}`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify({ audio: b64, mime: recorder.mimeType, language: langEl?.value||'auto' })});
                  if (res.ok) {
                    const data = await res.json();
                    if (data.ok && data.text) {
                      contentEl.value = (contentEl.value ? contentEl.value + ' ' : '') + data.text;
                    }
                  }
                } catch (_) {}
              }, 3000);
              recording = true; updateBtn(); setStatus('Recording… Speak now.');
            }
          } else {
            // stop
            if (SpeechRecognition && recognition && recording) {
              recognition.stop();
            }
            if (recorder && recording) { recorder.stop(); }
          }
        } catch (e) {
          setStatus('Mic permission denied or not available.', true);
          if (mediaStream) mediaStream.getTracks().forEach(t=>t.stop());
          recording = false; updateBtn();
        }
      });
    })();

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
        // Optional: client-side moderation check
        try {
          const mod = await fetch(`{{ url('/api/moderate') }}`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify({ text: content })});
          if (mod.ok) { const m = await mod.json(); if (m.bad) { alert('Please remove inappropriate words.'); return; } }
        } catch(e) {}
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

    // Inspire for post content
    const inspirePostBtn = document.querySelector('[data-inspire-post]');
    if (inspirePostBtn && postForm) {
      const contentEl = postForm.querySelector('textarea[name="content"]');
      const inspireUrl = '{{ route('api.inspire') }}';
      const think = async (prompt) => {
        inspirePostBtn.disabled = true; const old = inspirePostBtn.textContent; inspirePostBtn.textContent = '✨ Thinking...';
        try {
          const res = await fetch(inspireUrl, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify({ prompt })});
          const data = await res.json(); if (data?.text && contentEl) contentEl.value = data.text.trim();
        } finally { inspirePostBtn.disabled = false; inspirePostBtn.textContent = '✨ Inspire'; }
      };
      inspirePostBtn.addEventListener('click', ()=> think('Draft a short, positive group post about eco-friendly action.'));
      document.querySelectorAll('[data-inspire-topics-post] [data-topic]').forEach(chip=>{
        chip.addEventListener('click', ()=> think(`Write a short, friendly post about ${chip.textContent.trim()}.`));
      });
      const askWrap = document.querySelector('[data-inspire-ask-post]');
      if (askWrap){
        askWrap.querySelector('.btn').addEventListener('click', ()=>{
          const q = askWrap.querySelector('input')?.value?.trim(); if (q) think(q);
        });
      }
    }

    // Ajax: inspire comment content using Gemini
    document.querySelectorAll('[data-action="inspire"]').forEach(btn => {
      btn.addEventListener('click', async () => {
        const form = btn.closest('.comment-form');
        if (!form) return;
        const input = form.querySelector('input[name="content"]');
        const prompt = 'Suggest a helpful, friendly, short comment for this group post.';
        btn.disabled = true; btn.textContent = '✨ Thinking...';
        try {
          const res = await fetch(`{{ route('api.groups.inspire', $group->slug) }}`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify({ prompt })});
          const data = await res.json();
          if (data?.text) input.value = data.text;
        } finally { btn.disabled = false; btn.textContent = '✨ Inspire'; }
      });
    });

    // Read aloud post content using Azure TTS with play/pause toggle and caching per post
    (function(){
      const audioCache = new Map(); // postId -> { audio: HTMLAudioElement, srcB64: string }
      document.querySelectorAll('[data-action="tts"]').forEach(btn => {
        btn.addEventListener('click', async () => {
          const card = btn.closest('[data-post-card]');
          const postId = btn.getAttribute('data-post-id');
          const textEl = card?.querySelector('p');
          const text = (textEl?.textContent || '').trim();
          if (!text) { alert('Nothing to read.'); return; }

          // If cached, toggle play/pause
          if (audioCache.has(postId)) {
            const entry = audioCache.get(postId);
            const a = entry.audio;
            if (a.paused) { a.play(); btn.textContent = '⏸ Pause'; }
            else { a.pause(); btn.textContent = '▶️ Play'; }
            a.onended = () => { btn.textContent = '🔊 Read aloud'; };
            return;
          }

          btn.disabled = true; const old = btn.textContent; btn.textContent = '🔊 Generating...';
          try {
            const res = await fetch(`{{ route('api.groups.tts', $group->slug) }}`, { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify({ text })});
            if (!res.ok) throw new Error('TTS failed');
            const data = await res.json();
            if (data?.debug) { console.debug('TTS debug:', data.debug); }
            if (data?.audio) {
              const src = 'data:audio/mpeg;base64,'+data.audio;
              const audio = new Audio(src);
              audioCache.set(postId, { audio, srcB64: data.audio });
              audio.play();
              btn.textContent = '⏸ Pause';
              audio.onpause = () => { btn.textContent = '▶️ Play'; };
              audio.onplay = () => { btn.textContent = '⏸ Pause'; };
              audio.onended = () => { btn.textContent = '🔊 Read aloud'; };
            } else {
              alert('Could not generate audio. Check logs for details.');
              btn.textContent = old;
            }
          } catch(e) {
            alert('Text-to-Speech request failed.');
            btn.textContent = old;
          } finally { btn.disabled = false; }
        });
      });
    })();

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
