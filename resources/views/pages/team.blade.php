@extends('layouts.app')
@section('title','Our Team')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">our Team 01</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">Our Team 01</span>
			</div>
		</div>
	</section>
	<!-- Page banner area end here -->

	<!-- Team area start here -->
	<section class="team-three pt-130 pb-130">
		<div class="container">
			<div class="row g-4">
				@php
					$members = [
						['img' => '01.jpg', 'name' => 'Gregory K. Stanton', 'role' => 'Senior Engineer'],
						['img' => '02.jpg', 'name' => 'Sana p. Lesh', 'role' => 'Trainee Engineer'],
						['img' => '03.jpg', 'name' => 'Mark R. Stuckey', 'role' => 'Senior Engineer'],
						['img' => '04.jpg', 'name' => 'Nina M. Buxton', 'role' => 'Senior Engineer'],
						['img' => '05.jpg', 'name' => 'Jesus T. Anderson', 'role' => 'Senior Engineer'],
						['img' => '06.jpg', 'name' => 'Gladys M. Tyler', 'role' => 'Senior Engineer'],
					];
				@endphp
				@foreach ($members as $m)
					<div class="col-lg-4 col-md-6">
						<div class="team-three__item">
							<a href="{{ route('team.single') }}" class="image d-block">
								<img src="{{ asset('assets/images/team/'.$m['img']) }}" alt="image">
							</a>
							<div class="team-info">
								<a class="d-block" href="#0"><i class="fa-brands fa-facebook-f"></i></a>
								<a class="d-block" href="#0"><i class="fa-brands fa-twitter"></i></a>
								<a class="d-block" href="#0"><i class="fa-brands fa-linkedin-in"></i></a>
							</div>
							<div class="content">
								<h3><a href="{{ route('team.single') }}">{{ $m['name'] }}</a></h3>
								<span>{{ $m['role'] }}</span>
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
	<!-- Team area end here -->

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

