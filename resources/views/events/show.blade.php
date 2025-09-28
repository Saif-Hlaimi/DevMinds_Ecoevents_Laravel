@extends('layouts.app')

@section('title', $event->title . ' - EcoEvents')

@section('content')
<!-- Page banner area start here -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">{{ $event->title }}</h2>
        <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
            <a href="{{ route('home') }}">Home :</a>
            <a href="{{ route('events.index') }}">Events :</a>
            <span class="primary-color">{{ \Illuminate\Support\Str::limit($event->title, 20) }}</span>
        </div>
    </div>
</section>
<!-- Page banner area end here -->

<section class="pt-130 pb-130">
    <div class="container">
        <div class="row g-4">
            <!-- Event Image -->
            <div class="col-lg-6">
                <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/images/event/default.jpg') }}" 
                     alt="{{ $event->title }}" 
                     class="img-fluid rounded w-100 h-100" 
                     style="max-height:500px; object-fit:cover;">
            </div>

            <!-- Event Details -->
            <div class="col-lg-6" style="margin-right: -30px;">
                <h3>{{ $event->title }}</h3>
                <p>{{ $event->description }}</p>

                <p><strong>Date:</strong> {{ $event->date->format('M d, Y H:i') }}</p>
                <p><strong>Location:</strong> {{ $event->location ?? 'Online' }}</p>
                <p><strong>Organizer:</strong> {{ $event->user->name }}</p>
                <p><strong>Category:</strong> {{ $event->category }}</p>
                <p><strong>Type:</strong> {{ ucfirst($event->type) }}</p>

                @if($event->type === 'onsite')
                    <p><strong>Places disponibles:</strong> 
                        {{ $event->max_participants - $event->participants()->count() }} / {{ $event->max_participants }}
                    </p>
                @elseif($event->type === 'online')
                    <p><strong>Google Meet:</strong> 
                        <a href="{{ $event->meet_link }}" target="_blank">{{ $event->meet_link }}</a>
                    </p>
                @endif

                <!-- Participants List -->
                <div class="mt-3">
                    <strong>Participants ({{ $event->participants()->count() }}):</strong>
                    <ul>
                        @forelse($event->participants as $participant)
                            <li>{{ $participant->name }}</li>
                        @empty
                            <li>No participants registered yet.</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Admin/Organizer Actions -->
                @if (auth()->check() && (auth()->id() === $event->user_id || auth()->user()->role === 'admin'))
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('events.edit', $event) }}" class="btn-one">
                            <span>Edit</span> <i class="fa-solid fa-pen-to-square"></i>
                        </a>

                        <form action="{{ route('events.destroy', $event) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this event?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-two">
                                <span>Delete</span> <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                @endif
<!-- Comment Section -->
<div class="mt-5">
    <h4>Comments</h4>

    @auth
        @if(auth()->id() !== $event->user_id) 
            <!-- Formulaire pour les participants non-organisateurs -->
            <form action="{{ route('comments.store', $event) }}" method="POST">
    @csrf
    <div class="mb-3">
        <textarea class="form-control" name="content" rows="3" placeholder="Write your comment..." required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Post Comment</button>
</form>

        @else
            <p class="text-muted">You are the organizer and cannot post comments on your own event.</p>
        @endif
    @else
        <p><a href="{{ route('login') }}">Log in</a> to leave a comment.</p>
    @endauth

    <div class="mt-4">
        @forelse($event->comments as $comment)
            <div class="border p-3 mb-2 rounded d-flex justify-content-between align-items-start">
                <div>
                    <strong>{{ $comment->user->name }}</strong>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    <p>{{ $comment->content }}</p>
                </div>

                @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->role === 'admin'))
                    <!-- Bouton supprimer commentaire -->
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                @endif
            </div>
        @empty
            <p>No comments yet. Be the first to comment!</p>
        @endforelse
    </div>
</div>


</section>
@endsection
