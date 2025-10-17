@extends('layouts.admin')

@section('title', 'Détail Réclamation')

@section('content')
<div class="container">
    <h1 class="mb-4">Détail de la Réclamation</h1>

    <div class="card">
        <div class="card-header">
            Sujet : <strong>{{ $complaint->subject }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Message :</strong></p>
            <p>{{ $complaint->message }}</p>

            <hr>

            <p><strong>Type :</strong> {{ $complaint->type->name ?? '-' }}</p>
            <p><strong>Utilisateur :</strong> {{ $complaint->user->name }}</p>
            <p><strong>Statut :</strong> <span class="badge bg-info">{{ $complaint->status }}</span></p>
            <p><strong>Priorité :</strong> <span class="badge bg-warning text-dark">{{ $complaint->priority }}</span></p>
            <p><strong>Assignée à :</strong> {{ $complaint->assignee->name ?? '-' }}</p>

            @if($complaint->attachment_path)
                <hr>
                <p><strong>Pièce jointe :</strong></p>
                <a href="{{ asset('storage/' . $complaint->attachment_path) }}" target="_blank">Télécharger</a>
            @endif
        </div>
        <div class="card-footer d-flex justify-content-between">
            <a href="{{ route('admin.complaints.edit', $complaint) }}" class="btn btn-secondary">Modifier</a>
            <a href="{{ route('admin.complaints.index') }}" class="btn btn-outline-primary">Retour à la liste</a>
        </div>
    </div>
</div>
@endsection
