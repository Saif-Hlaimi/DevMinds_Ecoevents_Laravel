@extends('layouts.admin')
@section('title', 'Chat')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Chat</h3>
  <div class="alert alert-info" role="alert">
    This is an internal backoffice chat for admins and moderators to coordinate moderation, events, and support. It is not visible to public users.
  </div>

  <div class="card">
    <div class="card-body" style="max-height: 50vh; overflow:auto;">
      @forelse($messages as $m)
        <div class="d-flex align-items-start mb-2">
          <div>
            <div class="fw-semibold">{{ $m->user->name ?? 'User #'.$m->user_id }}</div>
            <div class="text-muted small">{{ $m->created_at->diffForHumans() }}</div>
            <div>{{ $m->message }}</div>
          </div>
          <div class="ms-auto">
            @if(auth()->id() === $m->user_id || (auth()->user()->role ?? null) === 'admin')
            <form method="POST" action="{{ route('dashboard.chat.destroy', $m) }}" onsubmit="return confirm('Delete message?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Delete</button>
            </form>
            @endif
          </div>
        </div>
        <hr class="my-2">
      @empty
        <div class="text-muted">No messages yet</div>
      @endforelse
    </div>
    <div class="card-footer">
      <form method="POST" action="{{ route('dashboard.chat.store') }}" class="d-flex gap-2">
        @csrf
        <input name="message" class="form-control" placeholder="Type a message..." required>
        <button class="btn btn-primary">Send</button>
      </form>
    </div>
  </div>
</div>
@endsection
