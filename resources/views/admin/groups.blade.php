@extends('layouts.admin')
@php use Illuminate\Support\Str; @endphp
@section('title','Groups')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Groups</h3>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

  <form method="GET" class="row g-2 align-items-center mb-3">
    <div class="col-md-4">
      <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Search groups by name...">
    </div>
    <div class="col-auto"><button class="btn btn-outline-primary">Search</button></div>
    @if(!empty($q))
      <div class="col-auto"><a href="{{ route('dashboard.admin.groups') }}" class="btn btn-link">Clear</a></div>
    @endif
  </form>

  @forelse($groups as $g)
    <div class="card mb-4" style="border-radius:12px; overflow:hidden;">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1">
              <h5 class="mb-0">{{ $g->name }}</h5>
              <span class="badge bg-{{ $g->privacy==='public' ? 'success' : 'secondary' }} text-uppercase">{{ $g->privacy }}</span>
            </div>
            <div class="text-muted small">Slug: {{ $g->slug }} · Creator: {{ optional($g->creator)->name ?? '—' }} (ID: {{ $g->created_by }}) · Members: {{ $g->members_count }} · Posts: {{ $g->posts_count }}</div>
          </div>
          <div class="text-end">
            <form method="POST" action="{{ route('dashboard.admin.groups.destroy', $g) }}" onsubmit="return confirm('Delete this group?')">
              @csrf @method('DELETE')
              <button class="btn btn-outline-danger btn-sm">Delete</button>
            </form>
          </div>
        </div>

        <div class="row g-3 mt-3">
          <div class="col-md-7">
            <form method="POST" action="{{ route('dashboard.admin.groups.update', $g) }}" class="row g-2">
              @csrf @method('PUT')
              <div class="col-12 col-md-8">
                <label class="form-label small mb-1">Name</label>
                <input name="name" value="{{ $g->name }}" class="form-control form-control-sm">
              </div>
              <div class="col-6 col-md-4">
                <label class="form-label small mb-1">Privacy</label>
                <select name="privacy" class="form-select form-select-sm">
                  @foreach(['public','private'] as $p)
                    <option value="{{ $p }}" @selected($g->privacy===$p)>{{ ucfirst($p) }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12">
                <label class="form-label small mb-1">Description</label>
                <textarea name="description" class="form-control" rows="2">{{ $g->description }}</textarea>
              </div>
              <div class="col-12">
                <button class="btn btn-success btn-sm">Save Changes</button>
                <a href="{{ route('groups.show', $g->slug) }}" class="btn btn-outline-secondary btn-sm" target="_blank">Open Group Page</a>
              </div>
            </form>
          </div>
          <div class="col-md-5">
            <div class="border rounded p-2" style="max-height: 280px; overflow:auto;">
              <div class="d-flex align-items-center justify-content-between mb-1">
                <strong>Recent Posts</strong>
                <span class="text-muted small">showing up to 5</span>
              </div>
              @forelse($g->posts as $p)
                <div class="p-2 rounded mb-2" style="background:#f8fafc; border:1px solid #e5e7eb;">
                  <div class="small text-muted">#{{ $p->id }} · {{ $p->created_at->diffForHumans() }} · by {{ optional($p->user)->name ?? '—' }}</div>
                  @if($p->content)
                    <div>{{ Str::limit($p->content, 140) }}</div>
                  @else
                    <div class="text-muted">(image only)</div>
                  @endif
                </div>
              @empty
                <div class="text-muted">No posts yet.</div>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="card"><div class="card-body">No groups found.</div></div>
  @endforelse

  <div>{{ $groups->links() }}</div>
</div>
@endsection
