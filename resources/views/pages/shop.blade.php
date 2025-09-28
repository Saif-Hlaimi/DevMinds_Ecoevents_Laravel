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
			<div class="row mb-4">
				<div class="col-12">
					<div class="d-flex justify-content-between align-items-center">
						<h3>Nos produits</h3>
						@auth
							<a href="{{ route('products.create') }}" class="btn btn-primary">
								<i class="fa-solid fa-plus me-2"></i>Ajouter un produit
							</a>
						@endauth
					</div>
				</div>
			</div>

			<div class="row g-4">
				<div class="col-lg-8">
					<div class="top-bar sub-bg mb-4 d-flex flex-wrap justify-content-between align-items-center main-bg radius10 px-4 py-3">
						<span>Showing 1–12 of {{ $products->total() }} results</span>
						<select name="select" id="select">
							<option value="select">Short by latest</option>
							<option value="select">Short by Ubdate</option>
							<option value="select">Short by New</option>
						</select>
					</div>
					<div class="product light">
						<div class="container">
							<div class="row g-4">
								@forelse ($products as $product)
									<div class="col-md-4">
										<div class="item">
											@if($product->image_path)
												<img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
											@else
												<img src="{{ asset('assets/images/product/product1.png') }}" alt="{{ $product->name }}">
											@endif
											<div class="content">
												<h4><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h4>
												<span class="text-primary fw-bold">{{ $product->formatted_price }}</span>
												@if($product->quantity > 0)
													<small class="text-success d-block">En stock</small>
												@else
													<small class="text-danger d-block">Rupture</small>
												@endif
											</div>
											<div class="icon">
												<a href="#0"><i class="fa-solid fa-heart"></i></a>
												@if($product->quantity > 0)
													<a href="{{ route('cart') }}" class="active"><i class="fa-solid fa-cart-shopping"></i></a>
												@else
													<a href="#0" class="disabled"><i class="fa-solid fa-cart-shopping"></i></a>
												@endif
											</div>
										</div>
									</div>
								@empty
									<div class="col-12 text-center py-5">
										<h5 class="text-muted">Aucun produit disponible</h5>
										<p class="text-muted">Revenez bientôt pour découvrir nos nouveaux produits.</p>
									</div>
								@endforelse
							</div>
							
							@if($products->hasPages())
								<div class="pt-30 bor-top mt-65">
									@if($products->onFirstPage())
										<span class="blog-pegi disabled">Précédent</span>
									@else
										<a class="blog-pegi" href="{{ $products->previousPageUrl() }}">Précédent</a>
									@endif

									@foreach(range(1, $products->lastPage()) as $page)
										<a class="blog-pegi {{ $page == $products->currentPage() ? 'active' : '' }}" 
										   href="{{ $products->url($page) }}">{{ $page }}</a>
									@endforeach

									@if($products->hasMorePages())
										<a class="blog-pegi" href="{{ $products->nextPageUrl() }}">
											<i class="fa-solid blog_pegi_arrow fa-arrow-right-long"></i>
										</a>
									@else
										<span class="blog-pegi disabled">
											<i class="fa-solid blog_pegi_arrow fa-arrow-right-long"></i>
										</span>
									@endif
								</div>
							@endif
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
								$popular = $products->take(3);
							@endphp
							@foreach($popular as $pp)
								<div class="recent-post p-0 bor-bottom pb-4 mb-4 sub-bg">
									<div class="img">
										@if($pp->image_path)
											<img src="{{ asset('storage/' . $pp->image_path) }}" alt="{{ $pp->name }}">
										@else
											<img src="{{ asset('assets/images/product/product1.png') }}" alt="{{ $pp->name }}">
										@endif
									</div>
									<div class="con">
										<h5><a href="{{ route('products.show', $pp) }}">{{ $pp->name }}</a></h5>
										<span>{{ $pp->formatted_price }}</span>
									</div>
								</div>
							@endforeach
							<h4 class="mb-30 mt-40">Hot Items</h4>
							<div class="swiper hot-items__slider">
								<div class="swiper-wrapper">
									@foreach($products->take(3) as $hotProduct)
										<div class="swiper-slide">
											<div class="image">
												@if($hotProduct->image_path)
													<img src="{{ asset('storage/' . $hotProduct->image_path) }}" alt="{{ $hotProduct->name }}">
												@else
													<img src="{{ asset('assets/images/product/product1.png') }}" alt="{{ $hotProduct->name }}">
												@endif
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