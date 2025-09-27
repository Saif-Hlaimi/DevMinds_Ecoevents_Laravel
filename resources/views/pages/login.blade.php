@extends('layouts.app')
@section('title','Login')
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
									<h2 class="mb-10">Welcome back</h2>
									<p class="text-muted mb-30">Connect to your EcoEvents account to continue.</p>
									<div class="form-area">
										<form action="{{ route('login.attempt') }}" method="POST" novalidate>
											@csrf
											@if ($errors->any())
												<div class="alert alert-danger py-2 px-3 mb-3">{{ $errors->first() }}</div>
											@endif
											<div class="position-relative mb-3">
												<i class="fa-regular fa-envelope position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
												<input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required style="padding-left:42px;">
											</div>
											<div class="position-relative mb-3">
												<i class="fa-solid fa-lock position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
												<input type="password" name="password" placeholder="Password" required style="padding-left:42px;">
											</div>
											<div class="d-flex align-items-center justify-content-between mb-2">
												<label class="d-flex align-items-center gap-2 mb-0">
													<input type="checkbox" name="remember" value="1"> <span>Remember me</span>
												</label>
												<a class="text-decoration-underline" href="{{ route('password.request') }}">Forgot password?</a>
											</div>
											<button class="auth-btn w-100 mt-2" type="submit">Sign in</button>
										</form>
										<div class="text-center mt-3"><span class="text-muted">No account?</span> <a href="{{ route('register') }}" class="text-decoration-underline">Create one</a></div>
										<span class="or pt-30 pb-40 d-block text-center">OR</span>
									</div>
									<div class="login__with auth-oauth">
										<a href="#0" class="d-flex align-items-center justify-content-center gap-2 py-2"><img src="{{ asset('assets/images/icon/google.svg') }}" alt=""> Continue with Google</a>
										<a class="mt-15 d-flex align-items-center justify-content-center gap-2 py-2" href="#0"><img src="{{ asset('assets/images/icon/facebook.svg') }}" alt=""> Continue with Facebook</a>
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
