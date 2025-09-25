@extends('layouts.app')
@section('title','Shop')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Shop Page</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">Shop</span>
			</div>
		</div>
	</section>
	<!-- Page banner area end here -->

	<!-- Shop page area start here -->
	<div class="shop pt-130 pb-130">
		<div class="container">
			<div class="row g-4">
				<div class="col-lg-8">
					<div class="top-bar sub-bg mb-4 d-flex flex-wrap justify-content-between align-items-center main-bg radius10 px-4 py-3">
						<span>Showing 1–12 of 15 results</span>
						<select name="select" id="select">
							<option value="select">Short by latest</option>
							<option value="select">Short by Ubdate</option>
							<option value="select">Short by New</option>
						</select>
					</div>
					<div class="product light">
						<div class="container">
							<div class="row g-4">
								@php
									$products = [
										['img' => 'product1.png', 'name' => 'Wood Bowls', 'old' => '$1099', 'price' => '$999'],
										['img' => 'product2.png', 'name' => 'Bamboo Trays', 'old' => '$259', 'price' => '$199'],
										['img' => 'product3.png', 'name' => 'Wood Frames', 'old' => '$299', 'price' => '$258'],
										['img' => 'product4.png', 'name' => 'Bamboo Utensils', 'old' => '$459', 'price' => '$358'],
										['img' => 'product5.png', 'name' => 'Wood Coasters', 'old' => '$1099', 'price' => '$999'],
										['img' => 'product6.png', 'name' => 'Bamboo Placemats', 'old' => '$259', 'price' => '$199'],
										['img' => 'product7.png', 'name' => 'Wood Boxes', 'old' => '$299', 'price' => '$258'],
										['img' => 'product8.png', 'name' => 'Bamboo Chopsticks', 'old' => '$1099', 'price' => '$999'],
										['img' => 'product9.png', 'name' => 'Wood Sculptures', 'old' => '$259', 'price' => '$199'],
										['img' => 'product10.png', 'name' => 'Bamboo Skewers', 'old' => '$299', 'price' => '$258'],
										['img' => 'product11.png', 'name' => 'Wood Mirrors', 'old' => '$259', 'price' => '$199'],
										['img' => 'product12.png', 'name' => 'Bamboo Rugs', 'old' => '$299', 'price' => '$258'],
									];
								@endphp
								@foreach ($products as $p)
									<div class="col-md-4">
										<div class="item">
											<img src="{{ asset('assets/images/product/'.$p['img']) }}" alt="image">
											<div class="content">
												<h4><a href="{{ route('product') }}">{{ $p['name'] }}</a></h4>
												<del>{{ $p['old'] }}</del> <span>- {{ $p['price'] }}</span>
											</div>
											<div class="icon">
												<a href="#0"><i class="fa-solid fa-heart"></i></a>
												<a href="{{ route('cart') }}" class="active"><i class="fa-solid fa-cart-shopping"></i></a>
											</div>
										</div>
									</div>
								@endforeach
							</div>
							<div class="pt-30 bor-top mt-65">
								<a class="blog-pegi" href="#0">01</a>
								<a class="blog-pegi active" href="#0">02</a>
								<a class="blog-pegi" href="#0">03</a>
								<a href="#0"><i class="fa-solid blog_pegi_arrow fa-arrow-right-long"></i></a>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="blog-slingle">
						<div class="right-item sub-bg">
							<h4 class="mb-30">Search</h4>
							<div class="search mb-40">
								<input type="text" placeholder="Search here. . .">
								<button><i class="fa-solid fa-search"></i></button>
							</div>
							<h4 class="mb-30">Categories</h4>
							<ul class="right_list mb-40">
								<li><a class="d-block pb-1 mb-2" href="#0">Business</a></li>
								<li><a class="d-block pb-1 mb-2" href="#0">Job Market</a></li>
								<li><a class="d-block pb-1 mb-2" href="#0">Marketing</a></li>
								<li><a class="d-block pb-1 mb-2" href="#0">News</a></li>
								<li><a class="d-block pb-1 mb-2" href="#0">Social Media</a></li>
								<li><a class="d-block pb-1 mb-2" href="#0">Trends</a></li>
								<li><a class="d-block" href="#0">Writing</a></li>
							</ul>
							<h4 class="mb-30">Popular Products</h4>
							@php
								$popular = [
									['img' => 'product1.png', 'name' => 'Wood Shelves', 'price' => '$299'],
									['img' => 'product2.png', 'name' => 'Bamboo Pendants', 'price' => '$299'],
									['img' => 'product3.png', 'name' => 'Wood Planters', 'price' => '$299'],
								];
							@endphp
							@foreach ($popular as $pp)
								<div class="recent-post p-0 bor-bottom pb-4 mb-4 sub-bg">
									<div class="img"><img src="{{ asset('assets/images/product/'.$pp['img']) }}" alt="image"></div>
									<div class="con">
										<h5><a href="{{ route('product') }}">{{ $pp['name'] }}</a></h5>
										<span>{{ $pp['price'] }}</span>
									</div>
								</div>
							@endforeach
							<h4 class="mb-30 mt-40">Hot Items</h4>
							<div class="swiper hot-items__slider">
								<div class="swiper-wrapper">
									@foreach ([8,9,10] as $i)
										<div class="swiper-slide">
											<div class="image">
												<img src="{{ asset('assets/images/product/product'.$i.'.png') }}" alt="image">
											</div>
										</div>
									@endforeach
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Shop page area end here -->

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
