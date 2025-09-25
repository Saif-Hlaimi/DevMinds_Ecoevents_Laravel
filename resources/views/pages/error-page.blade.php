@extends('layouts.app')
@section('title','404 Not Found')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">404 Not Found</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">404</span>
			</div>
		</div>
	</section>
	<!-- Page banner area end here -->

	<!-- Error area start here -->
	<section class="error-area pt-130 pb-130">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-8">
					<div class="error__content text-center sub-bg">
						<h1 class="display-3">404</h1>
						<h3 class="mt-10">Oops! The page you’re looking for isn’t here.</h3>
						<p class="mt-15">It might have been moved or deleted. Please check the URL or return to the homepage.</p>
						<a href="{{ route('home') }}" class="btn-one mt-30"><span>Back Home</span> <i class="fa-solid fa-angles-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Error area end here -->

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
