@extends('layouts.app')

@section('title', 'Profile - EcoEvents')

@section('content')
    <!-- Page banner area start here -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">My Profile</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Home :</a>
                <span class="primary-color">Profile</span>
            </div>
        </div>
    </section>
    <!-- Page banner area end here -->

    <section class="pt-130 pb-130">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="p-4 bg-image" data-background="{{ asset('assets/images/bg/achievement-bg2.jpg') }}" style="border-radius: 12px;">
                        <div class="text-white">
                            <h4 class="mb-2">{{ $user->name }}</h4>
                            <p class="mb-1">{{ $user->email }}</p>
                            <span class="badge bg-success text-uppercase">Role: {{ $user->role ?? 'user' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="p-4" style="border: 1px solid #e5e7eb; border-radius: 12px;">
                        <div class="section-header mb-4">
                            <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> Account details</h5>
                            <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">Welcome back, {{ \Illuminate\Support\Str::of($user->name)->before(' ') }}</h2>
                        </div>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-user pe-3 primary-color"></i>
                                    <div>
                                        <span class="text-muted d-block">Full name</span>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-envelope pe-3 primary-color"></i>
                                    <div>
                                        <span class="text-muted d-block">Email</span>
                                        <strong>{{ $user->email }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-id-badge pe-3 primary-color"></i>
                                    <div>
                                        <span class="text-muted d-block">Role</span>
                                        <strong class="text-uppercase">{{ $user->role ?? 'user' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fa-solid fa-calendar pe-3 primary-color"></i>
                                    <div>
                                        <span class="text-muted d-block">Member since</span>
                                        <strong>{{ optional($user->created_at)->format('M d, Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mt-40">
                            <a href="{{ route('home') }}" class="btn-one"><span>Go to home</span> <i class="fa-solid fa-angles-right"></i></a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-two"><span>Logout</span> <i class="fa-solid fa-right-from-bracket"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
