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
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Date and Time</label>
                                <input type="datetime-local" class="form-control" id="date" name="date" value="{{ old('date') }}" required>
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
                                @error('location')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Event Image (optional)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
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
@endsection