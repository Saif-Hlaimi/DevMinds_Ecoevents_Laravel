@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Notifications</h2>
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button class="btn btn-sm btn-outline-success">Mark all as read</button>
        </form>
    </div>
    <div class="list-group">
        @forelse($notifications as $n)
            @php $data = $n->data; @endphp
            <div class="list-group-item d-flex justify-content-between align-items-start {{ is_null($n->read_at) ? 'bg-light' : '' }}">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">{{ $data['message'] ?? 'Notification' }}</div>
                    @if(isset($data['group_slug']))
                        <a href="{{ route('groups.show', $data['group_slug']) }}" class="small text-decoration-underline">View group</a>
                    @endif
                    @if(isset($data['post_id']))
                        <span class="small text-muted ms-2">Post #{{ $data['post_id'] }}</span>
                    @endif
                    <div class="small text-muted mt-1">{{ $n->created_at->diffForHumans() }}</div>
                </div>
                <div>
                    @if(is_null($n->read_at))
                    <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-primary">Mark read</button>
                    </form>
                    @else
                        <span class="badge text-bg-secondary">Read</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted">No notifications yet</div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
