@extends('layouts.app')
@section('title', 'Donation Causes')
@section('content')
    <!-- Page banner area start here -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Donation Causes</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Home :</a>
                <span class="primary-color">Donation Causes</span>
            </div>
        </div>
    </section>

    <!-- Donation area start here -->
    <section class="donation-inner pb-130">
        <div class="container">
           
            <div class="row g-4">
                @forelse ($donationCauses as $cause)
                    @php
                        $percentage = min(($cause->raised_amount / $cause->goal_amount) * 100, 100);
                        $fullRadius = ($percentage >= 100) ? 'border-top-right-radius: 10px; border-bottom-right-radius: 10px;' : '';
                    @endphp
                    <div class="col-lg-4 wow fadeInUp" data-wow-duration="{{ 1.2 + $loop->index*0.2 }}s" data-wow-delay=".2s">
                        <div class="donation__item bor">
                            <div class="image mb-30">
                                <img src="{{ $cause->image ? asset('storage/' . $cause->image) : asset('assets/images/donation/0' . ($loop->index % 3 + 1) . '.jpg') }}" alt="{{ $cause->title }}" >
                            </div>
                           <div class="donation__item-progress-wrp mb-10" style="background-color: #e0e0e0; border-radius: 10px; height: 9px; overflow: hidden; display: flex; align-items: center;">
                                <div class="donation__item-progress-bar" style="width: {{ $percentage }}%; background-color: #4CAF50; height: 20px; border-top-left-radius: 10px; border-bottom-left-radius: 10px; {{ $fullRadius }} transition: width 0.3s ease;"></div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <h6>Raised ${{ number_format($cause->raised_amount, 2) }}</h6>
                                <h6>Goal ${{ number_format($cause->goal_amount, 2) }}</h6>
                            </div>
                            <h3><a href="{{ route('donation-causes.show', $cause->id) }}">{{ $cause->title }}</a></h3>
                            <p class="text-muted">{{ $cause->sdg }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a class="donation__item-arrow" href="{{ route('donation-causes.show', $cause->id) }}"><i class="fa-solid fa-arrow-right"></i></a>
                              
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">No donation causes available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection