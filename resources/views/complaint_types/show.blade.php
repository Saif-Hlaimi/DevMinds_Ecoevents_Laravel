@extends('layouts.app')

@section('title', 'Réclamations - '.ucfirst($complaintType->name))

@section('content')
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2>Réclamations — {{ ucfirst($complaintType->name) }}</h2>
        <div class="breadcrumb-list">
            <a href="{{ route('home') }}">Home :</a>
            <a href="{{ route('complaint-types.index') }}">Types :</a>
            <span class="primary-color">{{ ucfirst($complaintType->name) }}</span>
        </div>
    </div>
</section>

<section class="pt-130 pb-130">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-5">
            <h3 class="mb-0">Liste des réclamations ({{ $complaints->total() }})</h3>
            <a href="{{ route('complaints.create', ['type' => $complaintType->id]) }}" class="btn-one">
                <span>Nouvelle réclamation</span> <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        @if($complaints->count())
            <div class="row gy-4">
                @foreach($complaints as $complaint)
                    <div class="col-lg-4 col-md-6">
                        <div class="donation__item bor p-3">
                            <div class="image mb-30">
                                <img src="{{ asset('assets/images/event/default.jpg') }}" alt="{{ $complaint->subject }}">
                            </div>

                            <h3>
                                <a href="{{ route('complaints.show', $complaint) }}">
                                    {{ $complaint->subject }}
                                </a>
                            </h3>

                            <p>{{ Str::limit($complaint->message, 100) }}</p>

                            <p class="mb-1">
                                <strong>Priorité :</strong>
                                <span class="badge
                                    @if($complaint->priority == 'high') bg-danger
                                    @elseif($complaint->priority == 'low') bg-success
                                    @else bg-warning text-dark @endif">
                                    {{ ucfirst($complaint->priority ?? 'Moyenne') }}
                                </span>
                            </p>

                            <p class="mb-1"><strong>Date :</strong> {{ $complaint->created_at->format('d M Y') }}</p>
                            <p class="mb-1"><strong>Utilisateur :</strong> {{ $complaint->user->name ?? 'Anonyme' }}</p>

                            <a class="donation__item-arrow" href="{{ route('complaints.show', $complaint) }}">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">
                {{ $complaints->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted mb-0">Aucune réclamation pour ce type.</p>
            </div>
        @endif
    </div>
</section>
@endsection
