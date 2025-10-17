@extends('layouts.app')

@section('title', 'Events - EcoEvents')

@section('content')
<!-- 🌿 Page Banner -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container text-center">
        <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Explore Amazing Events</h2>
        <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
            <a href="{{ route('home') }}">Home :</a>
            <span class="primary-color">Events</span>
        </div>
    </div>
</section>

<section class="pt-130 pb-130">
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- 🔹 Sidebar Filters -->
            <div class="col-md-3 mb-4">
                <form action="{{ route('events.index') }}" method="GET" id="filters-form">
                    <h5><strong>📅 Filter by Posted Date</strong></h5><br>
                    <div class="form-check"><input class="form-check-input" type="radio" name="date_posted" value="all" {{ request('date_posted') == 'all' ? 'checked' : '' }}> <label class="form-check-label">All</label></div>
                    <div class="form-check"><input class="form-check-input" type="radio" name="date_posted" value="30" {{ request('date_posted') == '30' ? 'checked' : '' }}> <label class="form-check-label">Last 30 days</label></div>
                    <div class="form-check"><input class="form-check-input" type="radio" name="date_posted" value="7" {{ request('date_posted') == '7' ? 'checked' : '' }}> <label class="form-check-label">Last 7 days</label></div>
                    <div class="form-check mb-4"><input class="form-check-input" type="radio" name="date_posted" value="1" {{ request('date_posted') == '1' ? 'checked' : '' }}> <label class="form-check-label">Last 24h</label></div>

                    <h5><strong>📍 Filter by Location</strong></h5><br>
                    @php
                        $locations = \App\Models\Event::select('location')->distinct()->pluck('location');
                        $selectedLocations = request()->has('location') ? (array) request('location') : [];
                    @endphp
                    @foreach($locations as $location)
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="location[]" value="{{ $location }}" {{ in_array($location, $selectedLocations) ? 'checked' : '' }}>
                            <label class="form-check-label">{{ $location }}</label>
                        </div>
                    @endforeach

                    <br>
                    <h5><strong>💰 Filter by Price</strong></h5><br>
                    <div class="form-check"><input class="form-check-input" type="radio" name="price_filter" value="all" {{ request('price_filter') == 'all' ? 'checked' : '' }}> <label class="form-check-label">All</label></div>
                    <div class="form-check"><input class="form-check-input" type="radio" name="price_filter" value="free" {{ request('price_filter') == 'free' ? 'checked' : '' }}> <label class="form-check-label">Free</label></div>
                    <div class="form-check"><input class="form-check-input" type="radio" name="price_filter" value="paid" {{ request('price_filter') == 'paid' ? 'checked' : '' }}> <label class="form-check-label">Paid</label></div>
                    <div class="form-check mb-3"><input class="form-check-input" type="radio" name="price_filter" value="below_20" {{ request('price_filter') == 'below_20' ? 'checked' : '' }}> <label class="form-check-label">Under 20$</label></div>

                    <button type="submit" class="btn btn-success w-100">Apply filters</button>
                    <a href="{{ route('events.index') }}" class="btn btn-secondary mt-2 w-100">Reset</a>
                </form>
            </div>

            <!-- 🔸 Events Grid -->
            <div class="col-md-9">
                <!-- Search bar -->
                <form action="{{ route('events.index') }}" method="GET" class="d-flex gap-2 flex-wrap align-items-center mb-4">
                    <input type="text" name="search" class="form-control" placeholder="Search..." 
                        value="{{ request('search') }}" style="flex: 1;">
                    <select name="filter" class="form-control" style="width: 200px;" id="filter-select">
                        <option value="">Filter by</option>
                        <option value="title" {{ request('filter') == 'title' ? 'selected' : '' }}>Title</option>
                        <option value="date" {{ request('filter') == 'date' ? 'selected' : '' }}>Date</option>
                        <option value="location" {{ request('filter') == 'location' ? 'selected' : '' }}>Location</option>
                    </select>
                    <button type="submit" class="btn btn-success">Search</button>
                </form>

                <!-- Create + My Events buttons -->
                @if (auth()->check())
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <a href="{{ route('events.create') }}" class="btn-one d-flex align-items-center gap-2">
                            <i class="fa-solid fa-plus"></i> <span style="color:white">Create new event</span>
                        </a>

                       @if(request()->has('my_events'))
    <a href="{{ route('events.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="fa-solid fa-globe"></i> All Events
    </a>
@else
    <a href="{{ route('events.index', ['my_events' => 1]) }}" 
       class="btn btn-outline-success d-flex align-items-center gap-2">
        <i class="fa-solid fa-calendar-check"></i> My Posted Events
    </a>
@endif

                    </div>
                @endif

                <!-- 🎯 Events Section -->
                <h4 class="mb-3 text-success">
                    <i class="fa-regular fa-calendar"></i>
                    {{ request()->has('my_events') ? 'My Posted Events' : 'Events You Can Join' }}
                </h4>

                <div class="row g-4 mb-5">
                    @forelse ($events as $event)
                        <div class="col-lg-4 col-md-6">
                            <div class="donation__item bor event-card">
                                <div class="image-wrapper">
                                    <a href="{{ route('events.show', $event) }}">
                                        <img src="{{ $event->image ? asset('storage/' . $event->image) : asset('assets/images/event/default.jpg') }}" 
                                            alt="{{ $event->title }}">
                                    </a>
                                </div><br>
                                <div class="event-content">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <a href="{{ route('events.show', $event) }}" class="text-decoration-none flex-grow-1">
                                            <h5 class="card-title event-title text-break">{{ $event->title }}</h5>
                                        </a>
                                        @if($event->price > 0)
                                            <span class="event-price">{{ number_format($event->price, 2) }} $</span>
                                        @else
                                            <span class="event-free">Free</span>
                                        @endif
                                    </div>
                                    <p>{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
                                    <p><strong>Date:</strong> {{ $event->date->format('M d, Y H:i') }}</p>
                                    <p><strong>Location:</strong> {{ $event->location }}</p>
                                    <p><strong>Type:</strong> {{ ucfirst($event->type) }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center"><p>No events found.</p></div>
                    @endforelse
                </div>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 🎨 CSS --}}
<style>
.event-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.event-card:hover {transform: translateY(-6px); box-shadow: 0 8px 20px rgba(0,0,0,0.2);}
.image-wrapper {height: 220px; overflow: hidden;}
.image-wrapper img {width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;}
.image-wrapper img:hover {transform: scale(1.07);}
.event-title {font-size: 1.1rem; font-weight: 600; color: #2b3e6f; line-height: 1.4; word-wrap: break-word;}
.event-price, .event-free {
    font-weight: bold; padding: 6px 10px; border-radius: 6px; font-size: 1rem;
}
.event-price {background: linear-gradient(90deg, #1e90ff, #00bfff); color: white;}
.event-free {background: #5cb85c; color: #fff;}
.btn-one {
    display: inline-flex; align-items: center; gap: 8px;
    background: linear-gradient(90deg, #00b894, #0984e3);
    color: #fff; padding: 10px 20px; border-radius: 25px; text-decoration: none;
    transition: 0.3s;
}
.btn-one:hover {transform: scale(1.03); background: linear-gradient(90deg, #009432, #0652DD);}
.btn-outline-success {
    border: 2px solid #00b894; color: #00b894; font-weight: 600;
    border-radius: 25px; transition: 0.3s;
}
.btn-outline-success:hover {
    background: #00b894; color: #fff; transform: scale(1.05);
}
.btn-outline-secondary {
    border: 2px solid #777; color: #555; font-weight: 600; border-radius: 25px;
}
.btn-outline-secondary:hover {background: #777; color: #fff; transform: scale(1.05);}
.pagination {display: flex; justify-content: center; gap: 8px; margin-top: 40px; list-style: none;}
.page-link {color: #0a8754; background: #fff; border: 1px solid #ddd; border-radius: 12px; padding: 10px 16px;
    font-weight: 600; transition: all 0.25s ease; text-decoration: none;}
.page-link:hover {color: #fff!important; background: linear-gradient(135deg,#00b894,#0984e3);}
.page-item.active .page-link {color: #fff!important; background: linear-gradient(135deg,#009432,#44bd32);}
</style>

{{-- 🔄 Auto filter --}}
<script>
document.getElementById('filter-select').addEventListener('change', function() {
    this.form.submit();
});
document.querySelectorAll('input[name="price_filter"], input[name="date_posted"], input[name="location[]"]').forEach(input => {
    input.addEventListener('change', () => document.getElementById('filters-form').submit());
});
</script>
@endsection
