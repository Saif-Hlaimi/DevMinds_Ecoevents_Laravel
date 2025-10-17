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

                    <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" id="editEventForm">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="{{ old('title', $event->title) }}" required>
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description + bouton inspire -->
                        <!-- Description avec bouton Inspire -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0">Description</label>
                                <button type="button" class="btn btn-sm btn-outline-success" data-inspire-target="description" title="Generate a creative description">🌱 Generate</button>
                            </div>
                            <textarea name="description" id="description" class="form-control mt-2" rows="5" placeholder="Describe your event...">{{ old('description') }}</textarea>
                            <div class="form-text" id="mod-status-description"></div>
                            <div class="input-group mt-2" data-inspire-ask="description">
                                <input type="text" class="form-control" placeholder="Ask: e.g., write a description for a community cleanup event">
                                <button class="btn btn-outline-secondary" type="button">Ask</button>
                            </div>
                            @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Date and Time -->
                        <div class="mb-3">
                            <label for="date" class="form-label">Date and Time</label>
                            <input type="datetime-local" class="form-control" id="date" name="date"
                                   value="{{ old('date', optional($event->date)->format('Y-m-d\TH:i') ?? '') }}" required>
                            @error('date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location"
                                   value="{{ old('location', $event->location) }}">
                            @error('location') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-control" required>
                                @foreach(\App\Models\Event::categories() as $key => $label)
                                    <option value="{{ $key }}" {{ old('category', $event->category) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-control" required>
                                <option value="onsite" {{ old('type', $event->type) == 'onsite' ? 'selected' : '' }}>In-Person</option>
                                <option value="online" {{ old('type', $event->type) == 'online' ? 'selected' : '' }}>Online</option>
                            </select>
                            @error('type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Number of Places -->
                        <div class="mb-3" id="placesWrapper" style="{{ old('type', $event->type) === 'onsite' ? '' : 'display:none;' }}">
                            <label for="max_participants" class="form-label">Number of Places</label>
                            <input type="number" class="form-control" id="max_participants" name="max_participants"
                                   min="1" value="{{ old('max_participants', $event->max_participants) }}">
                            @error('max_participants') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Google Meet Link -->
                        <div class="mb-3" id="meetWrapper" style="{{ old('type', $event->type) === 'online' ? '' : 'display:none;' }}">
                            <label for="meet_link" class="form-label">Google Meet Link</label>
                            <input type="url" class="form-control" id="meet_link" name="meet_link"
                                   value="{{ old('meet_link', $event->meet_link) }}">
                            @error('meet_link') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Payment Option -->
                        <div class="mb-3">
                            <label for="is_paid" class="form-label">Event Payment</label>
                            <select name="is_paid" id="is_paid" class="form-control" required>
                                <option value="0" {{ (string) old('is_paid', (string) ($event->is_paid ?? 0)) === '0' ? 'selected' : '' }}>Free</option>
                                <option value="1" {{ (string) old('is_paid', (string) ($event->is_paid ?? 0)) === '1' ? 'selected' : '' }}>Paid</option>
                            </select>
                            @error('is_paid') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3" id="priceWrapper" style="{{ (int) old('is_paid', (int) ($event->is_paid ?? 0)) === 1 ? '' : 'display:none;' }}">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                   value="{{ old('price', $event->price) }}">
                            @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <!-- Event Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Event Image (optional)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            @if ($event->image)
                                <p class="mt-2">Current Image:<br>
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="Current Image" style="max-width: 200px; height: auto;">
                                </p>
                            @endif
                            @error('image') <span class="text-danger">{{ $message }}</span> @enderror
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

@push('scripts')
<script>
(function(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const inspireUrl = '{{ route('api.inspire') }}';
    const descBtn = document.querySelector('[data-inspire-target="description"]');
    const descField = document.getElementById('description');
    const statusDesc = document.getElementById('mod-status-description');

    async function inspireDescription(prompt) {
        if (!descField) return;
        if (descBtn) { 
            descBtn.disabled = true; 
            descBtn.textContent = '✨ Thinking...'; 
        }
        try {
            const res = await fetch(inspireUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ prompt })
            });
            const data = await res.json();
            if (data?.text) {
                descField.value = data.text.trim();
                descField.classList.add('border-success');
                setTimeout(() => descField.classList.remove('border-success'), 1200);
                statusDesc.textContent = '✅ Description generated successfully';
            }
        } catch (e) {
            statusDesc.textContent = '❌ Failed to generate text.';
        } finally {
            descBtn.disabled = false;
            descBtn.textContent = '✨ Inspire';
        }
    }

    // Default inspire click
    descBtn.addEventListener('click', () => {
        const title = document.querySelector('#title')?.value || 'eco event';
        const prompt = `Write an inspiring and coherent description for an event titled "${title}". Make it friendly and engaging.`;
        inspireDescription(prompt);
    });

    // "Ask" field
    document.querySelectorAll('[data-inspire-ask="description"] .btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.closest('[data-inspire-ask="description"]').querySelector('input');
            const question = input.value.trim();
            if (!question) return;
            inspireDescription(question);
        });
    });
})();
</script>
@endsection
