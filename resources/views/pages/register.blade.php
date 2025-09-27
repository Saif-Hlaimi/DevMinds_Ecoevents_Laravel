@extends('layouts.app')
@section('title','Register')
@section('content')
<section class="auth-hero pt-130 pb-130">
    <div class="container position-relative" style="z-index:2;">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="p-0 p-md-2">
                    <div class="row g-0 auth-card overflow-hidden">
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="h-100 w-100" style="background: url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat; min-height:380px;"></div>
                        </div>
                        <div class="col-lg-6 bg-white" style="border-radius: 0 18px 18px 0;">
                            <div class="p-4 p-md-5">
                                <h2 class="mb-10">Create account</h2>
                                <p class="text-muted mb-30">Join EcoEvents and start making an impact.</p>
                                <div class="form-area">
                                    <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                                        @csrf
                                        @if ($errors->any())
                                            <div class="alert alert-danger py-2 px-3 mb-3">{{ $errors->first() }}</div>
                                        @endif
                                        <div class="position-relative mb-3">
                                            <i class="fa-regular fa-user position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Full name" required style="padding-left:42px;">
                                        </div>
                                        <div class="position-relative mb-3">
                                            <i class="fa-regular fa-envelope position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                            <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required style="padding-left:42px;">
                                        </div>
                                        <div class="position-relative mb-3">
                                            <i class="fa-solid fa-phone position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone" style="padding-left:42px;">
                                        </div>
                                       <div class="position-relative mb-3">
    <i class="fa-solid fa-globe position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
    <input type="text" name="country" value="{{ old('country') }}" placeholder="Country" style="padding-left:42px;">
</div>
                                        <div class="position-relative mb-3">
                                            <i class="fa-solid fa-lock position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                            <input type="password" name="password" placeholder="Password" required style="padding-left:42px;">
                                        </div>
                                        <div class="position-relative mb-3">
                                            <i class="fa-solid fa-lock position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                            <input type="password" name="password_confirmation" placeholder="Confirm password" required style="padding-left:42px;">
                                        </div>
                                        <div class="position-relative mb-3">
                                            <i class="fa-solid fa-image position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
                                            <input type="file" name="profile_image" accept="image/*" style="padding-left:42px;">
                                        </div>
                                        <button class="auth-btn w-100 mt-2" type="submit">Create account</button>
                                    </form>
                                    <div class="text-center mt-3">
                                        <span class="text-muted">Already have an account?</span> 
                                        <a href="{{ route('login') }}" class="text-decoration-underline">Sign in</a>
                                    </div>
                                    <span class="or pt-30 pb-40 d-block text-center">OR</span>
                                </div>
                                <div class="login__with auth-oauth">
                                    <a href="#0" class="d-flex align-items-center justify-content-center gap-2 py-2">
                                        <img src="{{ asset('assets/images/icon/google.svg') }}" alt=""> Continue with Google
                                    </a>
                                    <a class="mt-15 d-flex align-items-center justify-content-center gap-2 py-2" href="#0">
                                        <img src="{{ asset('assets/images/icon/facebook.svg') }}" alt=""> Continue with Facebook
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</section>
@endsection
