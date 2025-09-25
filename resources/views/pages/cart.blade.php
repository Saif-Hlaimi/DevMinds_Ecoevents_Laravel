@extends('layouts.app')
@section('title','Cart')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Cart Page</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">Cart</span>
			</div>
		</div>
	</section>
	<!-- Page banner area end here -->

	<!-- cart page area start here -->
	<section class="cart-page pt-130 pb-130">
		<div class="container">

			<div class="shopping-cart bor sub-bg">

				<div class="column-labels py-3 px-4 d-flex justify-content-between align-items-center fw-bold text-white text-uppercase">
					<label class="product-details">Product</label>
					<label class="product-price">Price</label>
					<label class="product-quantity">Quantity</label>
					<label class="product-line-price">Total</label>
					<label class="product-removal">Edit</label>
				</div>

				@php
					$items = [
						['img' => '01.jpg', 'name' => 'Bamboo Rugs', 'price' => '12.99', 'qty' => 2, 'total' => '25.98'],
						['img' => '02.jpg', 'name' => 'Wood Planters', 'price' => '50.00', 'qty' => 1, 'total' => '50.00'],
						['img' => '03.jpg', 'name' => 'Wood Vases', 'price' => '45.99', 'qty' => 1, 'total' => '45.99'],
						['img' => '04.jpg', 'name' => 'Wood Clocks', 'price' => '99.99', 'qty' => 2, 'total' => '199.99'],
						['img' => '02.jpg', 'name' => 'Wood Tables', 'price' => '25.98', 'qty' => 1, 'total' => '25.98'],
					];
				@endphp

				@foreach ($items as $i)
					<div class="product p-4 {{ !$loop->first ? 'bor-bottom' : 'bor-top bor-bottom' }} d-flex justify-content-between align-items-center">
						<div class="product-details d-flex align-items-center">
							<img src="{{ asset('assets/images/shop/'.$i['img']) }}" alt="image">
							<h4 class="ps-4 text-capitalize">{{ $i['name'] }}</h4>
						</div>
						<div class="product-price">{{ $i['price'] }}</div>
						<div class="product-quantity">
							<input type="number" value="{{ $i['qty'] }}" min="1">
						</div>
						<div class="product-line-price">{{ $i['total'] }}</div>
						<div class="product-removal">
							<button class="remove-product">
								<i class="fa-solid fa-x heading-color"></i>
							</button>
						</div>
					</div>
				@endforeach

				<div class="totals">
					<div class="totals-item theme-color float-end mt-20">
						<span class="fw-bold text-uppercase py-2">cart total =</span>
						<div class="totals-value d-inline py-2 pe-2" id="cart-subtotal">399.97</div>
					</div>
				</div>

			</div>

			<!-- shopping-cart-mobile -->
			<div class="shopping-cart mobile-view bor sub-bg mt-40">
				@foreach ($items as $i)
					<div class="product p-4 {{ !$loop->last ? 'bor-bottom' : '' }}">
						<div class="product-details d-flex align-items-center">
							<img src="{{ asset('assets/images/shop/'.$i['img']) }}" alt="image">
							<h4 class="ps-4 text-capitalize">{{ $i['name'] }}</h4>
						</div>
						<div class="product-price">{{ $i['price'] }}</div>
						<div class="product-quantity">
							<input type="number" value="{{ $i['qty'] }}" min="1">
						</div>
						<div class="product-line-price">{{ $i['total'] }}</div>
						<div class="product-removal">
							<button class="remove-product">
								<i class="fa-solid fa-x heading-color"></i>
							</button>
						</div>
					</div>
				@endforeach

				<div class="totals">
					<div class="totals-item theme-color float-end">
						<span class="fw-bold text-uppercase py-2">cart total =</span>
						<div class="totals-value d-inline py-2 pe-2">399.97</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- cart page area end here -->

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
