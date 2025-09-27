@extends('layouts.app')
@section('title', 'Forgot Password')
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
                                    <h2 class="mb-10">Reset Your Password</h2>
                                    <p class="text-muted mb-30">Enter your email address to receive a password reset link.</p>
                                    <div class="form-area">
                                        <form action="{{ route('password.email') }}" method="POST" novalidate>
                                            @csrf
                                            @if (session('status'))
                                                <div class="alert alert-success py-2 px-3 mb-3">{{ session('status') }}</div>
                                            @endif
                                            @if ($errors->any())
                                                <div class="alert alert-danger py-2 px-3 mb-3">{{ $errors->first() }}</div>
                                            @endif
                                            <div class="position-relative mb-3">
                                                <i class="fa-regular fa-envelope position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required style="padding-left:42px;">
                                            </div>
                                            <button class="auth-btn w-100 mt-2" type="submit">Send Reset Link</button>
                                        </form>
                                        <div class="text-center mt-3">
                                            <a href="{{ route('login') }}" class="text-decoration-underline">Back to Login</a>
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