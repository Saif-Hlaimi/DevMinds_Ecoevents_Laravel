@extends('layouts.app')

@section('title', 'Services - EcoEvents')

@section('content')
    <!-- Page banner area start here -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Services</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Home :</a>
                <span class="primary-color">Services</span>
            </div>
        </div>
    </section>
    <!-- Page banner area end here -->

    <!-- Work area start here -->
    <section class="work work-five pt-130 pb-130">
        <div class="container">
            <div class="row g-4">
                @php
                    $services = [
                        ['icon' => 'fa-lightbulb-on', 'no' => '01', 'title' => 'Energy saving'],
                        ['icon' => 'fa-seedling', 'no' => '02', 'title' => 'Forest protection'],
                        ['icon' => 'fa-recycle', 'no' => '03', 'title' => 'Cleaning & Recycling'],
                        ['icon' => 'fa-raindrops', 'no' => '04', 'title' => 'water saving'],
                        ['icon' => 'fa-person-hiking', 'no' => '05', 'title' => 'Community Forestry'],
                        ['icon' => 'fa-leaf', 'no' => '06', 'title' => 'Individuals Plant'],
                        ['icon' => 'fa-paw', 'no' => '07', 'title' => 'Animal Rescue'],
                        ['icon' => 'fa-graduation-cap', 'no' => '08', 'title' => 'Education plan'],
                    ];
                @endphp

                @foreach ($services as $index => $s)
                    @php $delay = number_format(0.2 + ($index % 4) * 0.2, 1); @endphp
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 wow fadeInDown" data-wow-duration="1.2s" data-wow-delay=".{{ $delay }}s">
                        <div class="work-five__item">
                            <div class="work__item-icon mb-30">
                                <div class="work-five__icon">
                                    <i class="fa-light {{ $s['icon'] }}"></i>
                                </div>
                                <span>{{ $s['no'] }}</span>
                            </div>
                            <h3><a href="#0">{{ $s['title'] }}</a></h3>
                            <p>We maintain a busy network of forestry and social development staff along with local facilitators in the areas we work.</p>
                            <a class="work__item-arrow" href="#0"><i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Work area end here -->

    <!-- Achievement area start here -->
    <section class="achievement" data-background="{{ asset('assets/images/bg/achievement-bg2.jpg') }}">
        <div class="container">
            <div class="row g-4 align-items-center justify-content-between">
                <div class="col-lg-5 achievement__bor-right wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">
                    <div class="achievement__item">
                        <h2 class="text-white pt-3 pb-3">Our trees have been monitored</h2>
                    </div>
                </div>
                <div class="col-lg-2 achievement__bor-right wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                    <div class="achievement__item text-center">
                        <img src="{{ asset('assets/images/icon/achieve1.png') }}" alt="icon">
                        <h5>Trees planted</h5>
                        <span class="count">6,472,068</span>
                    </div>
                </div>
                <div class="col-lg-2 achievement__bor-right wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s">
                    <div class="achievement__item text-center">
                        <img src="{{ asset('assets/images/icon/achieve2.png') }}" alt="icon">
                        <h5>Families helped</h5>
                        <span class="count">38,768</span>
                    </div>
                </div>
                <div class="col-lg-2 wow fadeInUp" data-wow-duration="1.8s" data-wow-delay=".8s">
                    <div class="achievement__item text-center">
                        <img src="{{ asset('assets/images/icon/achieve3.png') }}" alt="icon">
                        <h5>CO2 captured (tonne)</h5>
                        <span class="count">1,193,210</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Achievement area end here -->
@endsection
