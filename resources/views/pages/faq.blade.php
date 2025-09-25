@extends('layouts.app')
@section('title','FAQ')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">FAQ Page</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">FAQ</span>
			</div>
		</div>
	</section>
	<!-- Page banner area end here -->

	<!-- FAQ area start here -->
	<section class="faq-area pt-130 pb-130">
		<div class="container">
			<div class="row g-4">
				<div class="col-lg-6">
					<div class="accordion-area mb-4 mb-lg-0" id="accordionExample">
						<div class="accordion__item">
							<h5 class="accordion__title" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
								How can I donate to support ecology projects?
							</h5>
							<div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
								<div class="accordion__body">
									You can donate via our website using secure payment methods. We also accept direct bank transfers and in-kind donations. Visit the Donations page for details.
								</div>
							</div>
						</div>
						<div class="accordion__item">
							<h5 class="accordion__title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								Where do my contributions go?
							</h5>
							<div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								<div class="accordion__body">
									Funds are allocated to tree planting, clean water initiatives, wildlife conservation, and educational workshops, following transparent reporting practices.
								</div>
							</div>
						</div>
						<div class="accordion__item">
							<h5 class="accordion__title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
								Can I volunteer for events and campaigns?
							</h5>
							<div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								<div class="accordion__body">
									Absolutely. Check our Events page to register for upcoming activities or contact us to join our volunteer network.
								</div>
							</div>
						</div>
						<div class="accordion__item">
							<h5 class="accordion__title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
								Do you provide corporate partnership opportunities?
							</h5>
							<div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								<div class="accordion__body">
									Yes. We collaborate with organizations on CSR projects, sponsorships, and environmental programs tailored to business goals.
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="accordion-area" id="accordionExample2">
						<div class="accordion__item">
							<h5 class="accordion__title" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
								How can I track project progress?
							</h5>
							<div id="collapseFive" class="accordion-collapse collapse show" data-bs-parent="#accordionExample2">
								<div class="accordion__body">
									We publish regular updates on the Projects and Blog pages, including photos, reports, and impact metrics.
								</div>
							</div>
						</div>
						<div class="accordion__item">
							<h5 class="accordion__title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
								Are my donations tax-deductible?
							</h5>
							<div id="collapseSix" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
								<div class="accordion__body">
									This depends on your country and regulations. We provide receipts and documentation where applicable.
								</div>
							</div>
						</div>
						<div class="accordion__item">
							<h5 class="accordion__title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
								How do I contact support?
							</h5>
							<div id="collapseSeven" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
								<div class="accordion__body">
									Use the Contact page form or email us directly. Our team responds within 24–48 hours.
								</div>
							</div>
						</div>
						<div class="accordion__item">
							<h5 class="accordion__title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
								Can I return a purchased item?
							</h5>
							<div id="collapseEight" class="accordion-collapse collapse" data-bs-parent="#accordionExample2">
								<div class="accordion__body">
									Yes, eligible products can be returned within 30 days in original condition. See our Shop policy for details.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- FAQ area end here -->

	<!-- Extra FAQ section start here -->
	<section class="faq-area2 pb-130">
		<div class="container">
			<div class="row g-4 align-items-center">
				<div class="col-lg-6">
					<div class="faq-area2__thumb">
						<img src="{{ asset('assets/images/faq/01.jpg') }}" alt="faq">
					</div>
				</div>
				<div class="col-lg-6">
					<div class="accordion-area" id="accordionExample3">
						<div class="accordion__item">
							<h5 class="accordion__title" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
								What is your mission?
							</h5>
							<div id="collapseNine" class="accordion-collapse collapse show" data-bs-parent="#accordionExample3">
								<div class="accordion__body">
									Our mission is to protect nature through community-led initiatives that promote sustainability and biodiversity.
								</div>
							</div>
						</div>
						<div class="accordion__item">
							<h5 class="accordion__title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
								How can businesses get involved?
							</h5>
							<div id="collapseTen" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">
								<div class="accordion__body">
									Partner with us for sponsorships, employee volunteering, and carbon offset programs.
								</div>
							</div>
						</div>
						<div class="accordion__item">
							<h5 class="accordion__title collapsed" data-bs-toggle="collapse" data-bs-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
								Do you offer educational content?
							</h5>
							<div id="collapseEleven" class="accordion-collapse collapse" data-bs-parent="#accordionExample3">
								<div class="accordion__body">
									Yes, we host workshops, webinars, and publish articles to raise environmental awareness.
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Extra FAQ section end here -->

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
