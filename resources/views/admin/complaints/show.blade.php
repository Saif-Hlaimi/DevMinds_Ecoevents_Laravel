@extends('layouts.admin')

@section('title', 'Complaint Details')

@section('content')
<div class="container">
    <h1 class="mb-4">Complaint Details</h1>

    <div class="card">
        <div class="card-header">
            Subject: <strong>{{ $complaint->subject }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Message:</strong></p>
            <p id="complaint-message">{{ $complaint->message }}</p>

            <div class="mt-3">
                <button class="btn btn-outline-primary btn-sm" onclick="translateMessage('fr')">Traduire en Français</button>
                <button class="btn btn-outline-success btn-sm" onclick="translateMessage('ar')">Traduire en Arabe</button>
            </div>

            <hr>
            <p><strong>Type:</strong> {{ $complaint->type->name ?? '-' }}</p>
            <p><strong>User:</strong> {{ $complaint->user->name }}</p>
            <p><strong>Status:</strong> <span class="badge bg-info">{{ $complaint->status }}</span></p>
            <p><strong>Priority:</strong> <span class="badge bg-warning text-dark">{{ $complaint->priority }}</span></p>

            @if($complaint->attachment_path)
                <hr>
                <p><strong>Attachment:</strong></p>
                <a href="{{ asset('storage/' . $complaint->attachment_path) }}" target="_blank">Download</a>
            @endif
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.complaints.edit', $complaint) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-primary">Back to list</a>
        </div>
    </div>
</div>

<script>
function translateMessage(lang) {
    fetch(`/admin/complaints/{{ $complaint->id }}/translate?lang=${lang}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('complaint-message').innerText = data.translated;
        })
        .catch(err => alert("Erreur de traduction : " + err));
}
</script>
@endsection