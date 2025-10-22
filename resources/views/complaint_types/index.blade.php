@extends('layouts.app')

@section('title', 'Types de réclamation - EcoEvents')

@section('head')
    <!-- Ajouter Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-p0EtXQHfpJ8h0kU4XQ7RrotrCTH1lYj4xH5V1OkiwHw+bhRYB2Yp2WmjU+N+Ieqh7TXE43/KlY6bk6T5J+K0kg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
<!-- Page banner area start here -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container text-center">
        <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Types de réclamation</h2>
        <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
            <a href="{{ route('home') }}">Home</a> :
            <span class="primary-color">Types</span>
        </div>
    </div>
</section>
<!-- Page banner area end here -->

<section class="pt-130 pb-130">
    <div class="container">
        <div class="row">
            <!-- Liste de types à gauche -->
            <div class="col-lg-6 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">Browse types</h4>
                    @auth
                       
                    @endauth
                </div>
                @if($types->count())
                    <div class="row g-4">
                        @foreach($types as $type)
                            <div class="col-12">
                                <div class="type-card d-flex align-items-center p-4 rounded shadow-sm">
                                    <div class="type-icon me-4 text-success">
                                        @php
                                            // Définir une icône FontAwesome pour chaque type
                                            $icons = [
                                                'général' => 'fa-solid fa-circle-info',
                                                'technique' => 'fa-solid fa-cogs',
                                                'paiement' => 'fa-solid fa-credit-card',
                                                'événement' => 'fa-solid fa-calendar-check',
                                                'service' => 'fa-solid fa-headset',
                                                'autre' => 'fa-solid fa-question'
                                            ];
                                            $iconClass = $icons[strtolower($type->name)] ?? 'fa-solid fa-circle';
                                        @endphp
                                        <i class="{{ $iconClass }} fa-3x"></i>
                                    </div>
                                    <div class="type-content flex-fill">
                                        <h5 class="mb-1 fw-bold">{{ ucfirst($type->name) }}</h5>
                                        <p class="mb-2 text-muted">{{ $type->complaints_count }} réclamation(s)</p>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('complaint-types.show', $type) }}" class="btn btn-success btn-sm">
                                               Show
                                            </a>
                                            <a href="{{ route('complaints.index', ['type' => $type->id]) }}" class="btn btn-outline-secondary btn-sm">
                                                Complaints
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <p class="text-muted mb-0">Aucun type trouvé.</p>
                    </div>
                @endif
            </div>

            <!-- Image à droite -->
            <div class="col-lg-6">
                <div class="h-100 d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/event/types-banner.jpg') }}" 
                         alt="Types Illustration" 
                         class="img-fluid rounded shadow" 
                         style="width: 100%; height: auto; max-height: 90vh; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.type-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: #fff;
}
.type-card:hover {
    transform: translateY(-7px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}
.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}
.btn-outline-secondary:hover {
    background-color: #6c757d;
    color: #fff;
}
</style>
@endsection
