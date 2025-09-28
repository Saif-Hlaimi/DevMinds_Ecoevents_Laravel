@extends('layouts.app')
@section('title', 'Edit Donation Cause - EcoEvents')
@section('content')
    <!-- Auth hero start (modernized) -->
    <section class="auth-hero pt-130 pb-130">
        <div class="container position-relative" style="z-index:2;">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="p-0 p-md-2">
                        <div class="row g-0 auth-card overflow-hidden">
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="h-100 w-100" style="background: url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat; min-height:380px;"></div>
                            </div>
                            <div class="col-lg-6 bg-white" style="border-radius: 0 18px 18px 0;">
                                <div class="p-4 p-md-5">
                                    <h2 class="mb-10">Edit Donation Cause</h2>
                                    <p class="text-muted mb-30">Update the details for this donation cause in EcoEvents.</p>
                                    <div class="form-area">
                                        <form action="{{ route('donation-causes.update', $donationCause->id) }}" method="POST" enctype="multipart/form-data" novalidate>
                                            @csrf
                                            @method('PUT')
                                            @if (session('status'))
                                                <div class="alert alert-success py-2 px-3 mb-3">{{ session('status') }}</div>
                                            @endif
                                            @if ($errors->any())
                                                <div class="alert alert-danger py-2 px-3 mb-3">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="position-relative mb-3">
                                                <i class="fa-solid fa-hand-holding-heart position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                                <input type="text" name="title" value="{{ old('title', $donationCause->title) }}" placeholder="Cause Title" required class="form-control" style="padding-left:42px;">
                                                @error('title')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="position-relative mb-3">
                                                <i class="fa-solid fa-info-circle position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                                <textarea name="description" placeholder="Cause Description" required class="form-control" style="padding-left:42px; height:150px; background-color: #ffffff !important;">{{ old('description', $donationCause->description) }}</textarea>
                                                @error('description')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="position-relative mb-3">
                                                <i class="fa-solid fa-image position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" placeholder="Choose Image" style="padding-left:42px;" readonly value="{{ $donationCause->image ? basename($donationCause->image) : '' }}">
                                                    <input type="file" name="image" accept="image/*" class="d-none" id="image-input">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('image-input').click()">Browse</button>
                                                </div>
                                                @if($donationCause->image)
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/' . $donationCause->image) }}" alt="Current Image" style="max-width: 200px;">
                                                    </div>
                                                @endif
                                                @error('image')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="position-relative mb-3">
                                                <i class="fa-solid fa-dollar-sign position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                                <input type="number" name="goal_amount" step="0.01" min="0.01" value="{{ old('goal_amount', $donationCause->goal_amount) }}" placeholder="Goal Amount" required class="form-control" style="padding-left:42px;">
                                                @error('goal_amount')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="position-relative mb-3">
                                                <i class="fa-solid fa-globe position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                                <input type="text" name="sdg" value="{{ old('sdg', $donationCause->sdg) }}" placeholder="Sustainable Development Goal (e.g., SDG1)" required class="form-control" style="padding-left:42px;">
                                                @error('sdg')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <button class="auth-btn w-100 mt-2" type="submit">Update Cause</button>
                                        </form>
                                        <div class="text-center mt-3">
                                            <a href="{{ route('donation-causes.index') }}" class="text-decoration-underline">Back to Donation Causes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Auth hero end -->

    <!-- Our info area start here -->
    <div class="our-info" data-background="https://images.unsplash.com/photo-1482192505345-5655af888cc4?q=80&w=1600&auto=format&fit=crop">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                    <a href="{{ route('home') }}" class="our-info__logo mb-4 mb-lg-0">
                        <img src="{{ asset('assets/images/logo/logo-light.svg') }}" alt="logo">
                    </a>
                </div>
                <div class="col-lg-5 wow fadeInDown" data-wow-duration="1.6s" data-wow-delay=".6s">
                    <div class="our-info__input">
                        <input type="text" placeholder="Your email Address">
                        <i class="fa-regular fa-envelope our-info__input-envelope"></i>
                        <i class="fa-solid fa-paper-plane our-info__input-plane"></i>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="our-info__social-icon float-lg-end float-none mt-4 mt-lg-0">
                        <a class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s" href="#0"><i class="fa-brands fa-facebook-f"></i></a>
                        <a class="wow fadeInUp" data-wow-duration="1.3s" data-wow-delay=".3s" href="#0"><i class="fa-brands fa-twitter"></i></a>
                        <a class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s" href="#0"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a class="wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".5s" href="#0"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our info area end here -->
@endsection