@extends('layouts.app')
@section('title','Events')
@section('content')
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Events 01</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">Events 01</span>
			</div>
		</div>
	</section>
	<section class="pt-130 pb-130">
		<div class="container">
			<div class="row g-4">
				@foreach ([1,2,3] as $i)
					<div class="col-lg-4 col-md-6">
						<div class="donation__item bor">
							<div class="image mb-30"><img src="{{ asset('assets/images/event/'.sprintf('0%d.jpg',$i)) }}" alt="image"></div>
							<h3><a href="{{ route('event.single') }}">Sample event {{ $i }}</a></h3>
							<a class="donation__item-arrow" href="{{ route('event.single') }}"><i class="fa-solid fa-arrow-right"></i></a>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	</section>
@endsection

