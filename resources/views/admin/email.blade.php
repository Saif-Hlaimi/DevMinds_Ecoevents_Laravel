@extends('layouts.admin')
@section('title', 'Email')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Email</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row g-3">
    <div class="col-lg-5">
      <div class="card">
        <div class="card-header">Compose</div>
        <div class="card-body">
          <form method="POST" action="{{ route('dashboard.email.store') }}">
            @csrf
            <div class="mb-2">
              <label class="form-label">From</label>
              <input name="from_email" type="email" class="form-control" value="{{ old('from_email', auth()->user()->email ?? '') }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">To</label>
              <input name="to_email" type="email" class="form-control" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Subject</label>
              <input name="subject" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Body</label>
              <textarea name="body" class="form-control" rows="6" required></textarea>
            </div>
            <button class="btn btn-primary">Save</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header">Inbox (Saved)</div>
        <div class="card-body table-responsive">
          <table class="table align-middle">
            <thead><tr><th></th><th>From</th><th>To</th><th>Subject</th><th>Date</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
              @forelse($emails as $m)
              <tr class="{{ $m->is_read ? '' : 'table-light' }}">
                <td>{!! $m->is_read ? '<span class="badge bg-success">Read</span>' : '<span class="badge bg-warning text-dark">New</span>' !!}</td>
                <td>{{ $m->from_email }}</td>
                <td>{{ $m->to_email }}</td>
                <td>{{ $m->subject }}</td>
                <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                <td class="text-end">
                  @if(!$m->is_read)
                  <form class="d-inline" method="POST" action="{{ route('dashboard.email.read', $m) }}">
                    @csrf
                    <button class="btn btn-sm btn-outline-secondary">Mark as read</button>
                  </form>
                  @endif
                  <form class="d-inline" method="POST" action="{{ route('dashboard.email.destroy', $m) }}" onsubmit="return confirm('Delete this email?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </td>
              </tr>
              <tr>
                <td></td><td colspan="5"><div class="small text-muted">{{ \Illuminate\Support\Str::limit($m->body, 300) }}</div></td>
              </tr>
              @empty
              <tr><td colspan="6" class="text-center text-muted">No emails</td></tr>
              @endforelse
            </tbody>
          </table>
          {{ $emails->links() }}
        </div>
      </div>
    </div>
  </div>
  
</div>
@endsection
