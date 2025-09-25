@extends('layouts.app')
@section('title','Checkout')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Checkout Page</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">Checkout</span>
			</div>
		</div>
	</section>
	<!-- Page banner area end here -->

	<!-- Checkout area start here -->
	<section class="checkout-area pt-130 pb-130">
		<div class="container">
			<div class="row g-4">
				<div class="col-lg-8">
					<div class="checkout__item-left sub-bg">
						<h3 class="mb-40">Billing Details</h3>
						<label class="mb-10" for="name">Your Name *</label>
						<input class="mb-20" id="name" type="text">
						<label class="mb-10" for="email">Email Address *</label>
						<input class="mb-20" id="email" type="email">
						<label class="mb-10" for="companyName">Company Name (Optional)</label>
						<input class="mb-20" id="companyName" type="text">
						<h5 class="mb-10">Country / Region *</h5>
						<select class="mb-20" name="subject">
							<option value="0">United state america</option>
							<option value="1">United Kingdom</option>
							<option value="2">Australia</option>
							<option value="3">Germany</option>
							<option value="4">France</option>
						</select>
						<label class="mb-10" for="streetAddress">Street Address *</label>
						<input placeholder="1837 E Homer M Adams Pkwy" class="mb-10" id="streetAddress" type="text">
						<input class="mb-20" id="streetAddress2" type="text">
						<label class="mb-10" for="townName">Town / City *</label>
						<input class="mb-20" id="townName" type="text">
						<h5 class="mb-10">State *</h5>
						<select class="mb-20" name="subject">
							<option value="0">Georgia / ohio / new york</option>
							<option value="1">Georgia</option>
							<option value="2">Ohio</option>
							<option value="3">New York</option>
							<option value="4">Texas</option>
						</select>
						<label class="mb-10" for="zipCode">ZIP Code *</label>
						<input class="mb-20" id="zipCode" type="number">
						<label class="mb-10" for="phone">Phone *</label>
						<input class="mb-20" id="phone" type="text">
						<div class="radio-btn">
							<span></span>
							<a class="ml-10 fw-bold" href="{{ route('register') }}">Create An Account?</a>
						</div>
						<div class="radio-btn mt-2 mb-30">
							<span class="opacity-75"></span>
							<p>Ship To A Different Address?</p>
						</div>
						<label class="mb-10" for="notes">Order Notes (Optional)</label>
						<textarea placeholder="Note About Your Order . . ." name="notes" id="notes"></textarea>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="checkout__item-right sub-bg">
						<h3 class="mb-40">Your Order</h3>
						<ul>
							<li class="bor-bottom pb-4">
								<h4>Products</h4>
								<h4>Subtotal</h4>
							</li>
							@foreach (range(1,5) as $n)
								<li class="bor-bottom py-4"><a href="#">Secretary desk</a> <span>$15.00</span></li>
							@endforeach
							<li class="bor-bottom py-4">
								<h4>Subtotal</h4>
								<h4>$999.00</h4>
							</li>
						</ul>
						<div class="py-4 bor-bottom">
							<h5 class="mb-10">Shipping Address</h5>
							<span>2801 Lafayette Blvd, Norfolk, Vermont <br>
								23509, united state</span>
						</div>
						<div class="radio-btn mt-30">
							<span class="opacity-75"></span>
							<p>Direct Bank Transfer</p>
						</div>
						<div class="radio-btn mt-2">
							<span></span>
							<a class="ml-10 fw-bold" href="#0">Check Payments</a>
						</div>
						<div class="radio-btn mt-2 pb-30 bor-bottom">
							<span class="opacity-75"></span>
							<p>Cash On Delivery</p>
						</div>
						<p class="pt-30 bor-top">Your personal data will be used to process your order, support your experience throughout this website.</p>
						<a href="#0" class="btn-one mt-35"><span>Place Order</span> <i class="fa-solid fa-angles-right"></i></a>

					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Checkout area end here -->

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
