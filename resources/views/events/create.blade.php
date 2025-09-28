@extends('layouts.app')

@section('title', 'Create Event - EcoEvents')

@section('content')
<!-- Page banner area start here -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Create Event</h2>
        <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
            <a href="{{ route('home') }}">Home :</a>
            <a href="{{ route('events.index') }}">Events :</a>
            <span class="primary-color">Create Event</span>
        </div>
    </div>
</section>
<!-- Page banner area end here -->

<section class="pt-130 pb-130">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-4" style="border: 1px solid #e5e7eb; border-radius: 12px;">
                    <h3>Create a New Event</h3>
                    <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="{{ old('title') }}" required>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Date and Time -->
                        <div class="mb-3">
                            <label for="date" class="form-label">Date and Time</label>
                            <input type="datetime-local" class="form-control" id="date" name="date" 
                                   value="{{ old('date') }}" required>
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" 
                                   value="{{ old('location') }}">
                            @error('location')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label> <br>
                            <select name="category" id="category" class="form-control" required>
                                <option value="" disabled {{ old('category') ? '' : 'selected' }}>-- Choose a category --</option>
                                @foreach(\App\Models\Event::categories() as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
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
                            <label for="type" class="form-label">Type</label> <br>
                            <select name="type" id="type" class="form-control" required>
                                <option value="" disabled {{ old('type') ? '' : 'selected' }}>-- Choose type --</option>
                                <option value="onsite" {{ old('type') == 'onsite' ? 'selected' : '' }}>In-Person</option>
                                <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Number of Places (always visible) -->
                        <div class="mb-3" id="placesWrapper"> <br>
                            <label for="max_participants" class="form-label">Number of Places</label>
                            <input type="number" class="form-control" id="max_participants" name="max_participants" 
                                   min="1" value="{{ old('max_participants', 1) }}">
                            @error('max_participants')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Google Meet Link (for online events) -->
                        <div class="mb-3" id="meetWrapper" style="display: none;">
                            <label for="meet_link" class="form-label">Google Meet Link</label>
                            <input type="url" class="form-control" id="meet_link" name="meet_link" 
                                   value="{{ old('meet_link') }}">
                            @error('meet_link')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Event Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Event Image (optional)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Create Event</button>
                            <a href="{{ route('events.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Script to toggle fields based on event type -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const typeSelect = document.getElementById("type");
    const maxParticipantsInput = document.getElementById("max_participants");
    const meetWrapper = document.getElementById("meetWrapper");
    const meetLinkInput = document.getElementById("meet_link");

    function toggleFields() {
        if (typeSelect.value === "onsite") {
            maxParticipantsInput.required = true;   
            meetWrapper.style.display = "none";
            meetLinkInput.required = false;
            meetLinkInput.value = "";
        } else if (typeSelect.value === "online") {
            maxParticipantsInput.required = false;  
            meetWrapper.style.display = "block";
            meetLinkInput.required = true;          
            maxParticipantsInput.value = "";
        } else {
            maxParticipantsInput.required = false;
            meetWrapper.style.display = "none";
            meetLinkInput.required = false;
            maxParticipantsInput.value = "";
            meetLinkInput.value = "";
        }
    }

    toggleFields();
    typeSelect.addEventListener("change", toggleFields);
});
</script>
@endsection
