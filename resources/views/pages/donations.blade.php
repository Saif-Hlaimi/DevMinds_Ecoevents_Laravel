@extends('layouts.app')
@section('title','Donations')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Donation 01</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">Donation 01</span>
			</div>
		</div>
	</section>

	<!-- Donation area start here -->
	<section class="donation-inner pb-130">
		<div class="container">
			<div class="image pt-130 pb-130 wow fadeInDown" data-wow-duration="1.2s" data-wow-delay=".2s">
				<img src="{{ asset('assets/images/donation/donation1.jpg') }}" alt="image">
			</div>
			<div class="row g-4">
				@foreach ([1,2,3] as $i)
					<div class="col-lg-4 wow fadeInUp" data-wow-duration="{{ 1.2 + ($i-1)*0.2 }}s" data-wow-delay=".2s">
						<div class="donation__item bor">
							<div class="image mb-30"><img src="{{ asset('assets/images/donation/'.sprintf('0%d.jpg',$i)) }}" alt="image"></div>
							<div class="donation__item-progress-wrp">
								<div class="d-flex align-items-center justify-content-between"><h6>Goal $3000</h6><h6>Raised $2000</h6></div>
								<div class="donation__item-progress-bar"></div>
							</div>
							<h3><a href="{{ route('donation.single') }}">The Mary Donate For Youth Health & plants</a></h3>
							<a class="donation__item-arrow" href="{{ route('donation.single') }}"><i class="fa-solid fa-arrow-right"></i></a>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
@endsection

