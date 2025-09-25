@extends('layouts.app')
@section('title','Blog')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">latest blog</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">latest news :</span>
				<span class="primary-color">Right Sidebar</span>
			</div>
		</div>
	</section>
	<!-- Page banner area end here -->

	<!-- Blog area start here (standard with right sidebar) -->
	<section class="blog-slingle blog-area pt-130 pb-130">
		<div class="container">
			<div class="row g-4">
				<div class="col-lg-8">
					<div class="item bor">
						<a href="{{ route('blog.single') }}" class="image d-block mb-30">
							<img src="{{ asset('assets/images/blog/single1.jpg') }}" alt="image">
						</a>
						<div class="content">
							<div class="d-flex align-items-center pb-4">
								<span class="blog__tag">Environment</span>
								<span class="bor-left ps-3 ms-3">By <strong>Max Trewhitt</strong> 2 weeks ago</span>
							</div>
							<h3><a href="{{ route('blog.single') }}">Group of young volunteers in park. they are planting</a></h3>
							<p>Tree planting is the act of planting young trees, shrubs, or other woody plants into the ground to establish new forests.</p>
							<a href="{{ route('blog.single') }}" class="btn-two mt-3"><span>read more</span> <i class="fa-solid fa-angles-right"></i></a>
						</div>
					</div>
					<!-- Repeat a couple posts -->
					@foreach ([2,3] as $n)
						<div class="item bor mt-4">
							<a href="{{ route('blog.single') }}" class="image d-block mb-30">
								<img src="{{ asset('assets/images/blog/single'.$n.'.jpg') }}" alt="image">
							</a>
							<div class="content">
								<div class="d-flex align-items-center pb-4">
									<span class="blog__tag">Environment</span>
									<span class="bor-left ps-3 ms-3">By <strong>Author</strong> 1 week ago</span>
								</div>
								<h3><a href="{{ route('blog.single') }}">Sample blog post {{ $n }}</a></h3>
								<p>We maintain a busy network of forestry and social development staff along with local facilitators in the areas we work.</p>
								<a href="{{ route('blog.single') }}" class="btn-two mt-3"><span>read more</span> <i class="fa-solid fa-angles-right"></i></a>
							</div>
						</div>
					@endforeach
				</div>
				<div class="col-lg-4">
					<aside class="sidebar">
						<div class="widget bor p-4 mb-4"><h5>Search</h5><input type="text" class="form-control" placeholder="Search..."></div>
						<div class="widget bor p-4 mb-4"><h5>Categories</h5><ul class="list-unstyled m-0"><li>Environment</li><li>Ecology</li><li>Conservation</li></ul></div>
						<div class="widget bor p-4"><h5>Tags</h5><div class="d-flex flex-wrap gap-2"><a href="#0" class="btn-two"><span>forest</span></a><a href="#0" class="btn-two"><span>trees</span></a><a href="#0" class="btn-two"><span>climate</span></a></div></div>
					</aside>
				</div>
			</div>
		</div>
	</section>
@endsection

