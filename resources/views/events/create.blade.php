@extends('layouts.app')

@section('title', 'Create Event - EcoEvents')

@section('content')
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2>Create Event</h2>
        <div>
            <a href="{{ route('home') }}">Home</a> :
            <a href="{{ route('events.index') }}">Events</a> :
            <span>Create Event</span>
        </div>
    </div>
</section>

<section class="pt-130 pb-130">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-4" style="border:1px solid #e5e7eb;border-radius:12px;">
                    <h3>Create a New Event</h3>

                    <form id="eventForm" action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}">
                            @error('title')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label class="form-label mb-0">Description</label>
                                <button type="button" class="btn btn-sm btn-outline-success" data-inspire-target="description">🌱 Generate</button>
                            </div>
                            <textarea name="description" id="description" class="form-control mt-2" rows="5" placeholder="Describe your event...">{{ old('description') }}</textarea>
                            <div class="form-text" id="mod-status-description"></div>
                            @error('description')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Date -->
                        <div class="mb-3">
                            <label for="date" class="form-label">Date & Time</label>
                            <input type="datetime-local" name="date" id="date" class="form-control" value="{{ old('date') }}">
                            @error('date')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}">
                            @error('location')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label> <br>
                            <select name="category" id="category" class="form-control">
                                <option value="" disabled selected>-- Choose a category --</option>
                                @foreach(\App\Models\Event::categories() as $key => $label)
                                    <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Type -->
                        <div class="mb-3"> <br>
                            <label for="type" class="form-label">Type</label> <br>
                            <select name="type" id="type" class="form-control">
                                <option value="" disabled selected>-- Choose type --</option>
                                <option value="onsite" {{ old('type')=='onsite' ? 'selected':'' }}>In-Person</option>
                                <option value="online" {{ old('type')=='online' ? 'selected':'' }}>Online</option>
                            </select>
                            @error('type')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Number of Places -->
                        <div class="mb-3" id="placesWrapper"> <br>
                            <label for="max_participants" class="form-label">Number of Places</label> <br>
                            <input type="number" name="max_participants" id="max_participants" class="form-control" min="1" value="{{ old('max_participants',1) }}">
                            @error('max_participants')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Google Meet -->
                        <div class="mb-3" id="meetWrapper"> <br>
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="meet_link" class="form-label mb-0">Google Meet Link</label> <br>
                                <button type="button" id="generateMeetBtn" class="btn btn-sm btn-primary">
                                    🔗 Generate Meet Link
                                </button>
                            </div>
                            <input type="url" name="meet_link" id="meet_link" class="form-control mt-2"
                                   placeholder="https://meet.google.com/xxxx-xxxx-xx"
                                   value="{{ old('meet_link') }}">
                        </div>

                        <!-- Paid / Free -->
                        <div class="mb-3"> <br>
                            <label for="is_paid" class="form-label">Event Type</label> <br>
                            <select name="is_paid" id="is_paid" class="form-control">
                                <option value="" disabled selected>-- Choose event type --</option>
                                <option value="0" {{ old('is_paid')=='0'? 'selected':'' }}>Free</option>
                                <option value="1" {{ old('is_paid')=='1'? 'selected':'' }}>Paid</option>
                            </select>
                        </div>

                        <!-- Price -->
                        <div class="mb-3" id="priceWrapper" style="display:none;"> <br>
                            <label for="price" class="form-label">Price (USD)</label>
                            <input type="number" name="price" id="price" class="form-control" step="0.01" value="{{ old('price') }}">
                            @error('price')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <!-- Image -->
                        <div class="mb-3"> <br>
                            <label for="image" class="form-label">Event Image</label> <br>
                            <input type="file" name="image" id="image" class="form-control">
                            @error('image')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100 mt-3">Create Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const meetBox = document.getElementById('meetWrapper');
    const generateMeetBtn = document.getElementById('generateMeetBtn');
    const meetInput = document.getElementById('meet_link');
    const isPaidSelect = document.getElementById('is_paid');
    const priceBox = document.getElementById('priceWrapper');
    const form = document.getElementById('eventForm');

    // toggle meet / price
    function toggleMeetBox() {
        meetBox.style.display = (typeSelect.value === 'online') ? 'block' : 'none';
    }
    function togglePriceBox() {
        priceBox.style.display = (isPaidSelect.value === '1') ? 'block' : 'none';
    }

    // generate meet
    generateMeetBtn.addEventListener('click', function() {
        const part = () => Math.random().toString(36).substring(2, 6);
        const link = `https://meet.google.com/${part()}-${part()}-${part()}`;
        meetInput.value = link;
        meetInput.classList.add('border-success');
        setTimeout(() => meetInput.classList.remove('border-success'), 1200);
    });

    // validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;
        document.querySelectorAll('.js-error').forEach(el => el.remove());

        const fields = [
            { id: 'title', label: 'Title' },
            { id: 'description', label: 'Description' },
            { id: 'date', label: 'Date & Time' },
            { id: 'category', label: 'Category' },
            { id: 'type', label: 'Type' },
            { id: 'is_paid', label: 'Event Type' }
        ];

        fields.forEach(f => {
            const el = document.getElementById(f.id);
            if (el && !el.value.trim()) {
                isValid = false;
                const msg = document.createElement('div');
                msg.className = 'text-danger js-error mt-1';
                msg.textContent = `${f.label} is required.`;
                el.insertAdjacentElement('afterend', msg);
            }
        });

        if (isValid) form.submit();
    });

    typeSelect.addEventListener('change', toggleMeetBox);
    isPaidSelect.addEventListener('change', togglePriceBox);
    toggleMeetBox();
    togglePriceBox();
});
</script>

<script>
(function(){
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const inspireUrl = '{{ route('api.inspire') }}';
    const descBtn = document.querySelector('[data-inspire-target="description"]');
    const descField = document.getElementById('description');
    const statusDesc = document.getElementById('mod-status-description');

    async function inspireDescription(prompt) {
        if (!descField) return;
        descBtn.disabled = true;
        descBtn.textContent = '✨ Thinking...';
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
            descBtn.textContent = '🌱 Generate';
        }
    }

    descBtn.addEventListener('click', () => {
        const title = document.querySelector('#title')?.value || 'eco event';
        const prompt = `Write an inspiring and coherent description for an event titled "${title}". Make it friendly and engaging.`;
        inspireDescription(prompt);
    });
})();
</script>
@endpush
@endsection
