@extends('layouts.app')

@section('title', $complaint->exists ? 'Edit Complaint' : 'New Complaint')

@section('content')
<!-- Page Banner -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2>{{ $complaint->exists ? 'Edit Complaint' : 'New Complaint' }}</h2>
        <div class="breadcrumb-list">
            <a href="{{ route('home') }}">Home</a> /
            <a href="{{ route('complaints.index') }}">Complaints</a> /
            <span>{{ $complaint->exists ? 'Edit' : 'Create' }}</span>
        </div>
    </div>
</section>

<!-- Main Form Section -->
<section class="pt-130 pb-130">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-4 border rounded-3">

                    <h3 class="mb-4">{{ $complaint->exists ? 'Edit Complaint' : 'New Complaint' }}</h3>

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" enctype="multipart/form-data"
                          action="{{ $complaint->exists ? route('complaints.update', $complaint) : route('complaints.store') }}">
                        @csrf
                        @if($complaint->exists)
                            @method('PUT')
                        @endif

                        <!-- Subject -->
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control"
                                   value="{{ old('subject', $complaint->subject) }}" required>
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required>{{ old('message', $complaint->message) }}</textarea>
                            <button type="button" id="improve-message-btn" class="btn btn-info mt-2">Améliorer le message</button>
                        </div>

                        <!-- Category & Priority -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Category</label>
                                <select name="category" class="form-select" required>
                                    @foreach(['general','personal','technical','billing'] as $cat)
                                        <option value="{{ $cat }}" @selected(old('category', $complaint->category ?? 'general') === $cat)>
                                            {{ ucfirst($cat) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Priority</label>
                                <select name="priority" class="form-select" required>
                                    @foreach(['low','medium','high'] as $p)
                                        <option value="{{ $p }}" @selected(old('priority', $complaint->priority ?? 'medium') === $p)>
                                            {{ ucfirst($p) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Attachment -->
                        <div class="mb-3">
                            <label class="form-label">Attachment (optional)</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>

                        <!-- Complaint Type -->
                        <div class="mb-3">
                            <label class="form-label">Complaint Type</label>
                            @php
                                $presetTypeId = old('complaint_type_id', $complaint->complaint_type_id ?? ($complaintType->id ?? null));
                            @endphp
                            @if($presetTypeId)
                                <input type="hidden" name="complaint_type_id" value="{{ $presetTypeId }}">
                                <input type="text" class="form-control" value="{{ optional($types->firstWhere('id', $presetTypeId))->name ?? 'Type' }}" readonly>
                            @else
                                <select name="complaint_type_id" class="form-select" required>
                                    <option value="" disabled selected>— Select —</option>
                                    @foreach($types as $t)
                                        <option value="{{ $t->id }}" @selected(old('complaint_type_id') == $t->id)>{{ ucfirst($t->name) }}</option>
                                    @endforeach
                                </select>
                                @error('complaint_type_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button class="btn btn-success" type="submit">
                                {{ $complaint->exists ? 'Update' : 'Save' }}
                            </button>
                            <a href="{{ route('complaints.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- JS pour améliorer le message -->
<script>
document.getElementById('improve-message-btn').addEventListener('click', async function() {
    const textarea = document.getElementById('message');
    const text = textarea.value;

    if (!text) return alert("Veuillez entrer un message à améliorer.");

    try {
        const response = await fetch("{{ route('complaints.improve-message') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ text })
        });

        const data = await response.json();
        if (data.rewritten) {
            textarea.value = data.rewritten;
        } else {
            alert("Impossible d'améliorer le message pour le moment.");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur lors de l'amélioration du message.");
    }
});
</script>

@endsection
