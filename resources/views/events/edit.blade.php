@extends('layouts.app')

@section('title', 'Edit ' . $event->title . ' - EcoEvents')

@section('content')
<!-- Page banner area start here -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Edit Event</h2>
        <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
            <a href="{{ route('home') }}">Home :</a>
            <a href="{{ route('events.index') }}">Events :</a>
            <span class="primary-color">Edit {{ \Illuminate\Support\Str::limit($event->title, 20) }}</span>
        </div>
    </div>
</section>
<!-- Page banner area end here -->

<section class="pt-130 pb-130">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-4" style="border: 1px solid #e5e7eb; border-radius: 12px;">
                    <h3>Edit {{ $event->title }}</h3>
                    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="{{ old('title', $event->title) }}" required>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Date and Time -->
                        <div class="mb-3">
                            <label for="date" class="form-label">Date and Time</label>
                            <input type="datetime-local" class="form-control" id="date" name="date" 
                                   value="{{ old('date', $event->date->format('Y-m-d\TH:i')) }}" required>
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="{{ old('location', $event->location) }}" required>
                            @error('location')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3"> <br>
                            <label for="category" class="form-label">Category</label> <br>
                            <select name="category" id="category" class="form-control" required>
                                @foreach(\App\Models\Event::categories() as $key => $label)
                                    <option value="{{ $key }}" {{ old('category', $event->category) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-3"> <br>
                            <label for="type" class="form-label">Type</label>  <br>
                            <select name="type" id="type" class="form-control" required>
                                <option value="onsite" {{ old('type', $event->type) == 'onsite' ? 'selected' : '' }}>In-Person</option>
                                <option value="online" {{ old('type', $event->type) == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Number of Places -->
                        <div class="mb-3" id="placesWrapper">  <br>
                            <label for="max_participants" class="form-label">Number of Places</label>
                            <input type="number" class="form-control" id="max_participants" name="max_participants" 
                                   min="1" value="{{ old('max_participants', $event->max_participants) }}">
                            @error('max_participants')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Google Meet Link -->
                        <div class="mb-3" id="meetWrapper" style="display: none;">
                            <label for="meet_link" class="form-label">Google Meet Link</label>
                            <input type="url" class="form-control" id="meet_link" name="meet_link" 
                                   value="{{ old('meet_link', $event->meet_link) }}">
                            @error('meet_link')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Event Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Event Image (optional)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            @if ($event->image)
                                <p class="mt-2">Current Image: 
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="Current Image" style="max-width: 100px;">
                                </p>
                            @endif
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Update Event</button>
                            <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script to toggle fields based on type -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const typeSelect = document.getElementById("type");
    const placesWrapper = document.getElementById("placesWrapper");
    const meetWrapper = document.getElementById("meetWrapper");

    function toggleFields() {
        if (typeSelect.value === "onsite") {
            placesWrapper.style.display = "block";
            meetWrapper.style.display = "none";
        } else if (typeSelect.value === "online") {
            placesWrapper.style.display = "none";
            meetWrapper.style.display = "block";
        }
    }

    toggleFields();
    typeSelect.addEventListener("change", toggleFields);
});
</script>
@endsection
