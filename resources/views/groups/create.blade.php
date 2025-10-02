@extends('layouts.app')
@section('title', isset($group) ? 'Edit Group' : 'Create Group')
@section('content')
<section class="page-banner bg-image pt-130 pb-130">
  <div class="container">
  <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">{{ isset($group) ? 'Edit group' : 'Create a group' }}</h2>
    <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
      <a href="{{ route('home') }}">Home :</a>
      <a href="{{ route('groups.index') }}">Groups :</a>
  <span class="primary-color">{{ isset($group) ? 'Edit' : 'Create' }}</span>
    </div>
  </div>
</section>

<section class="pt-130 pb-130">
  <div class="container">
    <div class="p-4" style="border:1px solid #e5e7eb;border-radius:12px;">
  <form action="{{ isset($group) ? route('groups.update',$group->slug) : route('groups.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @if(isset($group)) @method('PUT') @endif

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="row g-4">
          <div class="col-lg-8">
            <div class="mb-3">
              <label class="mb-2 d-flex justify-content-between align-items-center">Group name
                @auth
                <button type="button" class="btn btn-sm btn-outline-primary" data-inspire-target="name" title="Get name ideas">✨ Inspire</button>
                @endauth
              </label>
              <input type="text" name="name" class="w-100 form-control @error('name') is-invalid @enderror" value="{{ old('name', $group->name ?? '') }}" placeholder="Ex: Eco Cleanup Rabat" required>
              <div class="form-text" id="mod-status-name"></div>
              <div class="small text-muted mt-1" data-inspire-topics="name">
                <span class="badge bg-light text-dark me-1" data-topic>Cleanup</span>
                <span class="badge bg-light text-dark me-1" data-topic>Recycling</span>
                <span class="badge bg-light text-dark me-1" data-topic>Tree Planting</span>
                <span class="badge bg-light text-dark me-1" data-topic>Beach</span>
              </div>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="mb-2">Privacy</label>
              <select name="privacy" class="w-100 form-select @error('privacy') is-invalid @enderror" required>
                <option value="public" @selected(old('privacy',$group->privacy ?? 'public')==='public')>Public</option>
                <option value="private" @selected(old('privacy',$group->privacy ?? 'public')==='private')>Private</option>
              </select>
              @error('privacy')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="mb-2">Description</label>
              @auth
              <div class="d-flex justify-content-end mb-1">
                <button type="button" class="btn btn-sm btn-outline-primary" data-inspire-target="description" title="Draft description">✨ Inspire</button>
              </div>
              @endauth
              <textarea name="description" class="w-100 form-control @error('description') is-invalid @enderror" rows="5" placeholder="Describe your group's purpose, rules, and topics.">{{ old('description', $group->description ?? '') }}</textarea>
              <div class="form-text" id="mod-status-description"></div>
              <div class="input-group mt-2" data-inspire-ask="description">
                <input type="text" class="form-control" placeholder="Ask: e.g., write a friendly description for a recycling club for students">
                <button class="btn btn-outline-secondary" type="button">Ask</button>
              </div>
              @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            
            <div class="mb-3">
              <label class="mb-2">Cover image URL (optional)</label>
              <input type="url" name="cover_image" class="w-100 form-control @error('cover_image') is-invalid @enderror" value="{{ old('cover_image', $group->cover_image ?? '') }}" placeholder="https://...">
              @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="mb-2">Cover image file (optional)</label>
              <input type="file" name="cover_image_file" accept="image/*" class="w-100 form-control @error('cover_image_file') is-invalid @enderror">
              @error('cover_image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
              @isset($group)
                @php $cover = $group->cover_image_src; @endphp
                @if($cover)
                  <div class="mt-2" style="max-width:400px;border-radius:12px;overflow:hidden;">
                    <img src="{{ $cover }}" alt="cover" style="width:100%;height:200px;object-fit:cover;">
                  </div>
                @endif
              @endisset
            </div>
          </div>
          <div class="col-lg-4">
            <div class="p-3" style="background:#f9fafb;border:1px dashed #e5e7eb;border-radius:12px;">
              <div class="fw-bold mb-2">Tips</div>
              <ul class="mb-0 small">
                <li>Choose a clear, descriptive name</li>
                <li>Set privacy to match your audience</li>
                <li>Upload a cover image for better identity</li>
              </ul>
            </div>
            <button class="btn-one mt-3 w-100" type="submit">
              <span>{{ isset($group) ? 'Save changes' : 'Create group' }}</span> <i class="fa-solid fa-angles-right"></i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  (function(){
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
    const inspireUrl = '{{ route('api.inspire') }}';
    async function inspireFor(target, prompt){
      const el = document.querySelector(target === 'name' ? 'input[name="name"]' : 'textarea[name="description"]');
      if (!el) return;
      const btn = document.querySelector(`[data-inspire-target="${target}"]`);
      if (btn) { btn.disabled = true; const old = btn.textContent; btn.textContent = '✨ Thinking...';
        try {
          const res = await fetch(inspireUrl, { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json'}, body: JSON.stringify({ prompt })});
          const data = await res.json(); if (data?.text) el.value = data.text.trim();
        } finally { btn.disabled = false; btn.textContent = '✨ Inspire'; }
      }
    }
    // Topic chips for name
    document.querySelectorAll('[data-inspire-topics="name"] [data-topic]').forEach(chip=>{
      chip.addEventListener('click',()=>{
        const topic = chip.textContent.trim();
        inspireFor('name', `Suggest 5 catchy group names for an eco community focused on ${topic}. Return a single best option.`);
      });
    });
    // Inspire buttons
    document.querySelectorAll('[data-inspire-target]').forEach(btn=>{
      btn.addEventListener('click',()=>{
        const target = btn.getAttribute('data-inspire-target');
        const base = target==='name' ? 'Propose a short, clear group name about eco actions.' : 'Draft a concise, welcoming group description that explains goals, activities, and joining rules.';
        inspireFor(target, base);
      });
    });
    // Ask bars for description
    document.querySelectorAll('[data-inspire-ask="description"] .btn').forEach(btn=>{
      btn.addEventListener('click',()=>{
        const wrap = btn.closest('[data-inspire-ask="description"]');
        const inp = wrap.querySelector('input');
        const q = inp.value.trim(); if (!q) return;
        inspireFor('description', q);
      });
    });
    // Client-side moderation before submit with inline status (check name and description independently)
    const form = document.querySelector('form[action*="groups"][method="POST"], form[action*="groups"][method="post"]');
    if (form) {
      form.addEventListener('submit', async (e)=>{
        const name = (form.querySelector('input[name="name"]')?.value||'').trim();
        const desc = (form.querySelector('textarea[name="description"]')?.value||'').trim();
        const statusName = document.getElementById('mod-status-name');
        const statusDesc = document.getElementById('mod-status-description');
        const submitBtn = form.querySelector('button[type="submit"]');
        const setStatus = (el, msg, bad=false, loading=false) => {
          if (!el) return;
          el.textContent = msg || '';
          el.style.color = loading ? '#b91c1c' : (bad ? '#b91c1c' : '#6b7280');
        };
        try {
          e.preventDefault();
          submitBtn.disabled = true;
          // Check name
          setStatus(statusName, name ? 'Checking name for inappropriate language…' : '', false, !!name);
          let badName = false;
          if (name) {
            const r1 = await fetch('{{ route('api.moderate') }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json'}, body: JSON.stringify({ text: name })});
            if (r1.ok) { const d1 = await r1.json(); badName = !!d1.bad; }
            setStatus(statusName, badName ? 'Bad content detected in name.' : (name ? 'Name is clean.' : ''), badName);
          }
          // Check description
          setStatus(statusDesc, desc ? 'Checking description for inappropriate language…' : '', false, !!desc);
          let badDesc = false;
          if (desc) {
            const r2 = await fetch('{{ route('api.moderate') }}', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json'}, body: JSON.stringify({ text: desc })});
            if (r2.ok) { const d2 = await r2.json(); badDesc = !!d2.bad; }
            setStatus(statusDesc, badDesc ? 'Bad content detected in description.' : (desc ? 'Description is clean.' : ''), badDesc);
          }
          if (badName || badDesc) { submitBtn.disabled = false; return; }
          setStatus(statusName, (name ? 'Name is clean.' : ''));
          setStatus(statusDesc, (desc ? 'Description is clean.' : ''));
          form.submit();
        } catch(_){ submitBtn.disabled = false; }
      });
    }
  })();
</script>
@endpush
