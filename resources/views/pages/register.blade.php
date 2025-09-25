@extends('layouts.app')
@section('title','Register')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Register</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">Register</span>
			</div>
		</div>
	</section>
	<!-- Page banner area end here -->

	<!-- Login area start here -->
	<section class="login-area pt-130 pb-130">
		<div class="container">
			<div class="login__item">
				<div class="row g-4">
					<div class="col-xxl-8">
						<div class="login__image">
							<img src="{{ asset('assets/images/register/res-image1.jpg') }}" alt="image">
							<div class="btn-wrp">
								<a href="{{ route('login') }}">sign in</a>
								<a class="active" href="{{ route('register') }}">create account</a>
							</div>
						</div>
					</div>
					<div class="col-xxl-4">
						<div class="login__content">
							<h2 class="text-white mb-65">create account</h2>
							<div class="form-area login__form">
								<form action="#0">
									<input type="text" placeholder="User Name">
									<input class="mt-30" type="email" placeholder="Email">
									<input class="mt-30" type="password" placeholder="Enter Password">
									<input class="mt-30" type="password" placeholder="Enter Confirm Password">
									<button class="mt-30">Create Account</button>
									<div class="radio-btn mt-30">
										<span></span>
										<p>I accept your terms & conditions</p>
									</div>
								</form>
								<span class="or pt-30 pb-40">OR</span>
							</div>
							<div class="login__with">
								<a href="#0"><img src="{{ asset('assets/images/icon/google.svg') }}" alt=""> continue with google</a>
								<a class="mt-15" href="#0"><img src="{{ asset('assets/images/icon/facebook.svg') }}" alt=""> continue with facebook</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Login area end here -->

	<!-- Our info area start here -->
	<div class="our-info" data-background="{{ asset('assets/images/bg/our-info.jpg') }}">
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
