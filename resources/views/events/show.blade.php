@extends('layouts.app')

@section('title', $event->title . ' - EcoEvents')

@section('content')
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2>{{ $event->title }}</h2>
        <div>
            <a href="{{ route('home') }}">Home</a> :
            <a href="{{ route('events.index') }}">Events</a> :
            <span>{{ \Illuminate\Support\Str::limit($event->title, 20) }}</span>
        </div>
    </div>
</section>

<section class="pt-130 pb-130">
<div class="container">
    <div class="row g-4">
        <!-- Event Image -->
        <div class="col-lg-6">
            <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/images/event/default.jpg') }}" 
                 alt="{{ $event->title }}" class="img-fluid rounded w-100 h-100" style="max-height:500px; object-fit:cover;">
        </div>

        <!-- Event Info + Participants + Comments -->
        <div class="col-lg-6 d-flex flex-column">
            <h3>{{ $event->title }}</h3>
            <p>{{ $event->description }}</p>
            <p><strong>Date:</strong> {{ $event->date->format('M d, Y H:i') }}</p>
            <p><strong>Location:</strong> {{ $event->location ?? 'Online' }}</p>
            <p><strong>Organizer:</strong> {{ $event->user->name }}</p>
            <p><strong>Category:</strong> {{ $event->category }}</p>
            <p><strong>Type:</strong> {{ ucfirst($event->type) }}</p>
            <p><strong>Price:</strong> 
                @if($event->is_paid)
                    ${{ number_format($event->price, 2) }}
                @else
                    Free
                @endif
            </p>

            @if($event->type === 'onsite')
                <p><strong>Places disponibles:</strong> {{ $event->max_participants - $event->participants()->count() }} / {{ $event->max_participants }}</p>
            @elseif($event->type === 'online')
                <p><strong>Google Meet:</strong> <a href="{{ $event->meet_link }}" target="_blank">{{ $event->meet_link }}</a></p>
            @endif

          <!-- Approved Participants -->
<div class="mt-3">
    <strong>Participants ({{ $event->participants()->wherePivot('status','approved')->count() }}):</strong>
    <ul>
        @forelse($event->participants()->wherePivot('status','approved')->get() as $p)
            <li>
                {{ $p->name }}

            </li>
        @empty
            <li>No participants registered yet.</li>
        @endforelse
    </ul>
</div>


            <!-- Requested Participants for organizer -->
            @if(auth()->check() && auth()->id() === $event->user_id)
            <div class="mt-4">
                <h5>Requested Participants</h5>
                <ul class="list-group">
                    @forelse($event->participants()->wherePivot('status','pending')->get() as $user)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $user->name }}
                            <div class="d-flex gap-1">
                                <form action="{{ route('events.approve', [$event, $user->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                </form>
                                <form action="{{ route('events.reject', [$event, $user->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Decline</button>
                                </form>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item">No pending requests</li>
                    @endforelse
                </ul>
            </div>
            @endif

            <!-- Edit/Delete buttons (owner/admin) -->
            @if(auth()->check() && (auth()->id() === $event->user_id || auth()->user()->role === 'admin'))
                <div class="mt-3">
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-primary">Edit</a>
                    <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline;">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            @endif

            <!-- Participation Button -->
            @if(auth()->check() && auth()->id() !== $event->user_id)
                @php
                    $participant = $event->participants()->where('user_id', auth()->id())->first();
                @endphp
                @if($participant && $participant->pivot->status === 'approved')
                    <form action="{{ route('events.unregister', $event) }}" method="POST" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-warning">Cancel Participation</button>
                    </form>
                @elseif($participant && $participant->pivot->status === 'pending')
                    <button class="btn btn-secondary mt-2" disabled>Request Pending</button>
                @else
                    @if($event->is_paid)
                        <!-- 🟢 Redirection vers la page de paiement -->
                        <a href="{{ route('events.payment', $event) }}" class="btn btn-success mt-2 w-100">
                            Pay & Join ({{ number_format($event->price, 2) }} $)
                        </a>
                    @else
                        <!-- 🟢 Participation gratuite -->
                        <form action="{{ route('events.requestParticipation', $event) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                Request Participation
                            </button>
                        </form>
                    @endif
                @endif
            @endif
            @if(auth()->check())
    @php
        $participant = auth()->user();
        $isApproved = $event->participants()
            ->where('user_id', $participant->id)
            ->wherePivot('status', 'approved')
            ->exists();
    @endphp

    @if($isApproved)
        <a href="{{ route('events.certificate', [$event, $participant]) }}" class="btn btn-success mt-3" target="_blank">
            🎓 Download Certificate
        </a>
    @endif
@endif


            <!-- Comments Section -->
            <div class="mt-4">
                <h4>Comments</h4>
                @auth
                    @if(auth()->id() !== $event->user_id)
                                            <form id="commentForm" action="{{ route('comments.store', $event) }}" method="POST" class="mb-3" novalidate>
                            @csrf
                            <textarea name="content" id="commentContent"
                                class="form-control @error('content') is-invalid @enderror"
                                rows="2" placeholder="Write a comment...">{{ old('content') }}</textarea>
                            <div id="commentError" class="text-danger mt-1 fw-bold" style="display:none;">
                                 Comment is required and must be at least 3 characters.
                            </div>
                            @error('content')
                                <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="btn btn-primary mt-2">Post Comment</button>
                        </form>

                    @else
                        <p class="text-muted">Organizer cannot comment on their own event.</p>
                    @endif
                @else
                    <p><a href="{{ route('login') }}">Log in</a> to comment.</p>
                @endauth

                <div class="mt-2">
                    @forelse($event->comments as $comment)
                        <div class="border p-2 mb-2 rounded">
                            <strong>{{ $comment->user->name }}</strong>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            <p>{{ $comment->content }}</p>

                            <!-- Like / Dislike Buttons -->
                            <div class="d-flex gap-2 mb-2">
                                <form action="{{ route('comments.react', [$comment, 'like']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $comment->reactions->where('user_id', auth()->id())->first()?->type === 'like' ? 'btn-success' : 'btn-outline-success' }}">
                                        👍 {{ $comment->reactions->where('type', 'like')->count() }}
                                    </button>
                                </form>

                                <form action="{{ route('comments.react', [$comment, 'dislike']) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $comment->reactions->where('user_id', auth()->id())->first()?->type === 'dislike' ? 'btn-danger' : 'btn-outline-danger' }}">
                                        👎 {{ $comment->reactions->where('type', 'dislike')->count() }}
                                    </button>
                                </form>
                            </div>

                            @if(auth()->check() && (auth()->id()==$comment->user_id || auth()->user()->role=='admin' || auth()->id()==$event->user_id))
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete comment?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <p>No comments yet.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('commentForm');
    const textarea = document.getElementById('commentContent');
    const errorBox = document.getElementById('commentError');

    form.addEventListener('submit', function(e) {
        const content = textarea.value.trim();

        if (content.length < 3) {
            e.preventDefault();
            errorBox.style.display = 'block';
            textarea.classList.add('is-invalid');
        } else {
            errorBox.style.display = 'none';
            textarea.classList.remove('is-invalid');
        }
    });
});
</script>
@endsection
