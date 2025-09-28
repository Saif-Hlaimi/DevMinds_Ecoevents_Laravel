@extends('layouts.admin')
@section('title','Groups')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Groups</h3>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
  <div class="card">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead><tr><th>Name</th><th>Privacy</th><th>Members</th><th>Posts</th><th class="text-end">Actions</th></tr></thead>
        <tbody>
          @foreach($groups as $g)
          <tr>
            <td>
              <form method="POST" action="{{ route('dashboard.admin.groups.update', $g) }}" class="row g-2 align-items-center">
                @csrf @method('PUT')
                <div class="col"><input name="name" value="{{ $g->name }}" class="form-control form-control-sm"></div>
            </td>
            <td>
              <select name="privacy" class="form-select form-select-sm">
                @foreach(['public','private'] as $p)
                  <option value="{{ $p }}" @selected($g->privacy===$p)>{{ ucfirst($p) }}</option>
                @endforeach
              </select>
            </td>
            <td>{{ $g->members_count }}</td>
            <td>{{ $g->posts_count }}</td>
            <td class="text-end">
              <button class="btn btn-success btn-sm">Save</button>
              </form>
              <form method="POST" action="{{ route('dashboard.admin.groups.destroy', $g) }}" class="d-inline" onsubmit="return confirm('Delete this group?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
          <tr>
            <td colspan="5">
              <div class="small text-muted">Slug: {{ $g->slug }} | Creator ID: {{ $g->created_by }}</div>
              <div class="mt-2">
                <label class="form-label">Description</label>
                <form method="POST" action="{{ route('dashboard.admin.groups.update', $g) }}" class="d-flex gap-2">
                  @csrf @method('PUT')
                  <textarea name="description" class="form-control" rows="2">{{ $g->description }}</textarea>
                  <button class="btn btn-outline-primary">Update Description</button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $groups->links() }}</div>
  </div>
</div>
@endsection
