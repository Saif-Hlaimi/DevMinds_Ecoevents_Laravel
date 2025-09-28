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

    <!-- Main content with sidebar -->
    <section class="pt-130 pb-130">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-md-3 mb-4">
                    <form action="{{ route('events.index') }}" method="GET">
                        <h5><strong>Filter by posted date</strong></h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="date_posted" value="all" 
                                {{ request('date_posted') == 'all' ? 'checked' : '' }}>
                            <label class="form-check-label">All</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="date_posted" value="30" 
                                {{ request('date_posted') == '30' ? 'checked' : '' }}>
                            <label class="form-check-label">Last 30 days</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="date_posted" value="7" 
                                {{ request('date_posted') == '7' ? 'checked' : '' }}>
                            <label class="form-check-label">Last 7 days</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="radio" name="date_posted" value="1" 
                                {{ request('date_posted') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label">Last 24h</label>
                        </div>

                        <h5><strong>Filter by location</strong></h5>
                        @php
                            $locations = \App\Models\Event::select('location')->distinct()->pluck('location');
                            $selectedLocations = request()->has('location') ? (array) request('location') : [];
                        @endphp
                        @foreach($locations as $location)
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="location[]" value="{{ $location }}" 
                                    {{ in_array($location, $selectedLocations) ? 'checked' : '' }}>
                                <label class="form-check-label">{{ $location }}</label>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-success mt-3 w-100">Apply filters</button>
                        <a href="{{ route('events.index') }}" class="btn btn-secondary mt-2 w-100">Reset</a>
                    </form>
                </div>

                <!-- Events List -->
                <div class="col-md-9">
                    <!-- Search bar -->
                    <form action="{{ route('events.index') }}" method="GET" 
                          class="d-flex gap-2 flex-wrap align-items-center mb-4">
                        <input type="text" name="search" class="form-control" placeholder="Search..." 
                               value="{{ request('search') }}" style="flex: 1;">
                        <select name="filter" class="form-control" style="width: 200px;">
                            <option value="">Filter by</option>
                            <option value="title" {{ request('filter') == 'title' ? 'selected' : '' }}>Title</option>
                            <option value="date" {{ request('filter') == 'date' ? 'selected' : '' }}>Date</option>
                            <option value="location" {{ request('filter') == 'location' ? 'selected' : '' }}>Location</option>
                        </select>
                        <button type="submit" class="btn btn-success">Search</button>
                    </form>

                    <!-- Create button -->
                    @if (auth()->check())
                        <div class="mb-4">
                            <a href="{{ route('events.create') }}" class="btn-one">
                                <span>Create new event</span> <i class="fa-solid fa-plus"></i>
                            </a>
                        </div>
                    @endif

                    <!-- Events -->
                    <div class="row g-4">
                        @forelse ($events as $event)
                            <div class="col-lg-4 col-md-6">
                                <div class="donation__item bor">
                                    <div class="image mb-30">
                                        <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/images/event/default.jpg') }}" 
                                             alt="{{ $event->title }}">
                                    </div>
                                    <h3><a href="{{ route('events.show', $event) }}">{{ $event->title }}</a></h3>
                                    <p>{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
                                    <p><strong>Date:</strong> {{ $event->date->format('M d, Y H:i') }}</p>
                                    <p><strong>Location:</strong> {{ $event->location }}</p>
                                    <p><strong>Type:</strong> {{ ucfirst($event->type) }}</p>
                                    @if($event->type === 'onsite')
                                        <p><strong>Number of Places:</strong> {{ $event->max_participants }}</p>
                                    @elseif($event->type === 'online')
                                        <p><strong>Meet Link:</strong> <a href="{{ $event->meet_link }}" target="_blank">{{ $event->meet_link }}</a></p>
                                    @endif
                                    <p><strong>Organizer:</strong> {{ $event->user->name }}</p>

                                    @if (auth()->check() && (auth()->id() === $event->user_id || auth()->user()->role === 'admin'))
                                        <div class="d-flex gap-2 mt-3">
                                            <a href="{{ route('events.edit', $event) }}" class="btn-one">
                                                <span>Edit</span> <i class="fa-solid fa-pen-to-square"></i>
                                            </a>

                                            <form action="{{ route('events.destroy', $event) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-two" 
                                                        onclick="return confirm('Delete this event ?')" 
                                                        style="background-color:red;border-color:red;">
                                                    <span style="color:white;">Delete</span> 
                                                    <i class="fa-solid fa-trash" style="color:white;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p>No events found.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
