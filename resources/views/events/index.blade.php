@extends('layouts.app')

@section('title', 'Events - EcoEvents')

@section('content')
    <!-- Page banner area start here -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Events</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Home :</a>
                <span class="primary-color">Events</span>
            </div>
        </div>
    </section>
    <!-- Page banner area end here -->

    <section class="pt-130 pb-130">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (auth()->check())
                <div class="mb-4">
                    <a href="{{ route('events.create') }}" class="btn-one"><span>Create New Event</span> <i class="fa-solid fa-plus"></i></a>
                </div>
            @endif
            <div class="row g-4">
                @forelse ($events as $event)
                    <div class="col-lg-4 col-md-6">
                        <div class="donation__item bor">
                            <div class="image mb-30">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/images/event/default.jpg') }}" alt="{{ $event->title }}">
                            </div>
                            <h3><a href="{{ route('events.show', $event) }}">{{ $event->title }}</a></h3>
                            <p>{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
<p><strong>Date:</strong> {{ $event->date->format('M d, Y H:i') }}</p>
                            <p><strong>Location:</strong> {{ $event->location }}</p>
                            <p><strong>Organizer:</strong> {{ $event->user->name }}</p>
                           @if (auth()->check() && (auth()->id() === $event->user_id || auth()->user()->role === 'admin'))
    <div class="d-flex gap-2 mt-3">
        <a href="{{ route('events.edit', $event) }}" class="btn-one">
            <span>Edit</span> <i class="fa-solid fa-pen-to-square"></i>
        </a>

        <form action="{{ route('events.destroy', $event) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn-two" onclick="return confirm('Are you sure you want to delete this event?')">
                <span>Delete</span> <i class="fa-solid fa-trash"></i>
            </button>
        </form>
    </div>
@endif

                            
                            <a class="donation__item-arrow" href="{{ route('events.show', $event) }}"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p>No events found.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection