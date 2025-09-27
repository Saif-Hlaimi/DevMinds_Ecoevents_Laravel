@extends('layouts.app')

@section('title', '{{ $event->title }} - EcoEvents')

@section('content')
    <!-- Page banner area start here -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">{{ $event->title }}</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Home:</a>
                <a href="{{ route('events.index') }}">Events :</a>
                <span class="primary-color">{{ \Illuminate\Support\Str::limit($event->title, 20) }}</span>
            </div>
        </div>
    </section>
    <!-- Page banner area end here -->

    <section class="pt-130 pb-130">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/images/event/default.jpg') }}" alt="{{ $event->title }}" class="img-fluid rounded">
                </div>
                <div class="col-lg-6">
                    <h3>{{ $event->title }}</h3>
                    <p>{{ $event->description }}</p>
                    <p><strong>Date:</strong> {{ $event->date->format('M d, Y H:i') }}</p>
                    <p><strong>Location:</strong> {{ $event->location }}</p>
                    <p><strong>Organizer:</strong> {{ $event->user->name }}</p>
                    @if (auth()->check() && (auth()->id() === $event->user_id || auth()->user()->role === 'admin'))
                        <div class="d-flex gap-2 mt-3">
                            <a href="{{ route('events.edit', $event) }}" class="btn-one"><span>Edit</span> <i class="fa-solid fa-pen-to-square"></i></a>
                            @if (auth()->user()->role === 'admin')
                                <form action="{{ route('events.destroy', $event) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-two" onclick="return confirm('Are you sure you want to delete this event?')"><span>Delete</span> <i class="fa-solid fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection