@extends('layouts.app')

@section('title', 'About Us - EcoEvents')

@section('content')
    <!-- Page banner area start here -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">About Us</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Home :</a>
                <span class="primary-color">About Us</span>
            </div>
        </div>
    </section>
    <!-- Page banner area end here -->

    <!-- About area start here -->
    <section class="about-two pt-130 pb-130">
        <div class="about-two__shape-right d-none d-md-block bobble__animation">
            <img src="{{ asset('assets/images/shape/about-two-shape-right.png') }}" alt="shape">
        </div>
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-xl-6">
                    <div class="about-two__image">
                        <img src="{{ asset('assets/images/about/01.jpg') }}" alt="image">
                        <div class="sm-image d-none d-sm-block">
                            <img src="{{ asset('assets/images/about/02.png') }}" alt="image">
                        </div>
                        <div class="info d-none d-sm-block bg-image" data-background="{{ asset('assets/images/about/about-ex-bg.png') }}">
                            <h2><span class="count">25</span>+</h2>
                            <span class="year">Years Experience</span>
                        </div>
                        <div class="stroke-text d-none d-sm-block">
                            <h2>since 1980</h2>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="about-two__right-item">
                        <div class="section-header mb-4">
                            <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> ABOUT US</h5>
                            <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">We Are Here to Increase Your modern life With planting</h2>
                            <p class="wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s">Tree planting is the act of planting young trees, shrubs, or other woody plants into the ground to establish new forests or enhance existing ones. It is a crucial component of environmental conservation and reforestation efforts aimed at combating deforestation, mitigating climate change, improving biodiversity.</p>
                        </div>
                        <h3 class="pb-30 wow fadeInUp" data-wow-duration="1.8s" data-wow-delay=".8s">Here are some key aspects of tree planting</h3>
                        <div class="d-flex align-items-center justify-content-between flex-wrap mb-10">
                            <ul class="wow fadeInDown" data-wow-duration="1.2s" data-wow-delay=".2s">
                                <li class="mb-30"><img class="pe-2" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="icon"> Reforestation</li>
                                <li class="mb-30"><img class="pe-2" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="icon"> Soil Conservation</li>
                            </ul>
                            <ul class="wow fadeInDown" data-wow-duration="1.4s" data-wow-delay=".4s">
                                <li class="mb-30"><img class="pe-2" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="icon"> Climate Change Mitigation</li>
                                <li class="mb-30"><img class="pe-2" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="icon"> Biodiversity Conservation</li>
                            </ul>
                            <ul class="wow fadeInDown" data-wow-duration="1.6s" data-wow-delay=".6s">
                                <li class="mb-30"><img class="pe-2" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="icon"> Air Quality Improvement</li>
                                <li class="mb-30"><img class="pe-2" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="icon"> Economic Benefits</li>
                            </ul>
                        </div>
                        <p class="wow fadeInDown" data-wow-duration="1.8s" data-wow-delay=".8s">It's important to note that tree planting should be carried out thoughtfully, considering factors such as the suitability of tree species to the local ecosystem</p>
                        <div class="about_info d-flex align-items-center pt-65 wow fadeInUp" data-wow-duration="1.9s" data-wow-delay=".9s">
                            <a href="{{ route('about') }}" class="btn-one"><span>More About us</span> <i class="fa-solid fa-angles-right"></i></a>
                            <span class="bor-left d-none d-sm-block mx-4"></span>
                            <div class="info d-flex flex-wrap align-items-center">
                                <i class="fa-solid fa-phone-volume ring-animation"></i>
                                <div class="about_info_con">
                                    <span class="d-block text-capitalize">call any time</span>
                                    <a href="tel:+912659302003">+91 2659 302 003</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About area end here -->

    <!-- Achievement area start here -->
    <section class="achievement" data-background="{{ asset('assets/images/bg/achievement-bg2.jpg') }}">
        <div class="container">
            <div class="row g-4 align-items-center justify-content-between">
                <div class="col-lg-5 achievement__bor-right wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">
                    <div class="achievement__item">
                        <h2 class="text-white pt-3 pb-3">Our trees have been monitored</h2>
                    </div>
                </div>
                <div class="col-lg-2 achievement__bor-right wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                    <div class="achievement__item text-center">
                        <img src="{{ asset('assets/images/icon/achieve1.png') }}" alt="icon">
                        <h5>Trees planted</h5>
                        <span class="count">6,472,068</span>
                    </div>
                </div>
                <div class="col-lg-2 achievement__bor-right wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s">
                    <div class="achievement__item text-center">
                        <img src="{{ asset('assets/images/icon/achieve2.png') }}" alt="icon">
                        <h5>Families helped</h5>
                        <span class="count">38,768</span>
                    </div>
                </div>
                <div class="col-lg-2 wow fadeInUp" data-wow-duration="1.8s" data-wow-delay=".8s">
                    <div class="achievement__item text-center">
                        <img src="{{ asset('assets/images/icon/achieve3.png') }}" alt="icon">
                        <h5>CO2 captured (tonne)</h5>
                        <span class="count">1,193,210</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Achievement area end here -->

    <!-- History area start here -->
    <section class="about-two history-area pt-130 pb-130">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-xl-6">
                    <div class="about-two__right-item">
                        <div class="section-header mb-4">
                            <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> OUR History</h5>
                            <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">History of Our Organization</h2>
                        </div>
                        <h5 class="pb-30 wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">Elit ullamcorper dignissim cras tincidunt lobortis feugiat vibe</h5>
                        <p class="wow fadeInDown" data-wow-duration="1.8s" data-wow-delay=".8s">It's important to note that tree planting should be carried out thoughtfully, considering factors such as the suitability of tree species to the local ecosystem</p>
                        <div class="d-flex align-items-center gap-5 flex-wrap mt-45 mb-30">
                            <ul class="wow fadeInDown" data-wow-duration="1.4s" data-wow-delay=".4s">
                                <li><img class="pe-2" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="icon"> Mentoring</li>
                            </ul>
                            <ul class="wow fadeInDown" data-wow-duration="1.4s" data-wow-delay=".4s">
                                <li><img class="pe-2" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="icon"> Donating</li>
                            </ul>
                            <ul class="wow fadeInDown" data-wow-duration="1.4s" data-wow-delay=".4s">
                                <li><img class="pe-2" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="icon"> Volunteering</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="image">
                        <img src="{{ asset('assets/images/history/history-image.png') }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- History area end here -->

    <!-- Team area start here (About variant) -->
    <section class="team team-two pt-130 pb-130 bg-image" data-background="{{ asset('assets/images/bg/banner1.jpg') }}">
        <div class="team-two__shape sway__animation d-none d-lg-block">
            <img class="wow slideInLeft" data-wow-duration="2s" data-wow-delay="1s" src="{{ asset('assets/images/shape/team2.png') }}" alt="shape">
        </div>
        <div class="container">
            <div class="section-header text-center">
                <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> OUR TEAM MEMBERS</h5>
                <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">foresty awesome team</h2>
            </div>
            <div class="row g-4">
                @for ($i = 1; $i <= 4; $i++)
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="{{ number_format(1.2 + ($i-1)*0.2,1) }}s" data-wow-delay=".{{ number_format(0.2 + ($i-1)*0.2,1) }}s">
                        <div class="team__item team-two__item">
                            <div class="team__item-image">
                                <img src="{{ asset('assets/images/team/'.sprintf('%02d',$i).'.png') }}" alt="image">
                                <div class="team__item-image-icon social-icon">
                                    <a href="#0"><i class="fa-brands fa-facebook-f"></i></a>
                                    <a href="#0"><i class="fa-brands fa-twitter"></i></a>
                                    <a href="#0"><i class="fa-brands fa-linkedin-in"></i></a>
                                    <a href="#0"><i class="fa-brands fa-youtube"></i></a>
                                </div>
                            </div>
                            <h3><a href="#0">{{ ['Dana A. Hutchison','Bonnie J. Britt','Francis A. Cote','Mario L. Lawhorn'][$i-1] }}</a></h3>
                            <span>{{ ['Founder','Forest Officer','Garden Maker','Co - Founder'][$i-1] }}</span>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>
    <!-- Team area end here -->

    <!-- Involved area start here (Help area) -->
    <section class="involve-two help-area mt-130 mb-130">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="image pe-lg-5 pe-0">
                        <img src="{{ asset('assets/images/involve/help2.png') }}" alt="image">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="involve-two__item help-area__item">
                        <div class="section-header mb-4">
                            <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> how we help</h5>
                            <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">we’re here to help nature</h2>
                        </div>
                        <div class="accordion" id="accordionExample-two">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOneTwo">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOneTwo" aria-expanded="true" aria-controls="collapseOneTwo">What is Forestry?</button>
                                </h2>
                                <div id="collapseOneTwo" class="accordion-collapse collapse show" aria-labelledby="headingOneTwo" data-bs-parent="#accordionExample-two">
                                    <div class="accordion-body">Tree planting is the act of planting young trees or shrubs into the ground to establish new forests or enhance existing ones.</div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingTwoTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwoTwo" aria-expanded="false" aria-controls="collapseTwoTwo">How can I donate?</button>
                                </h2>
                                <div id="collapseTwoTwo" class="accordion-collapse collapse" aria-labelledby="headingTwoTwo" data-bs-parent="#accordionExample-two">
                                    <div class="accordion-body">You can donate by joining our campaigns and supporting reforestation efforts in your community.</div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingThreeTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThreeTwo" aria-expanded="false" aria-controls="collapseThreeTwo">Can I volunteer?</button>
                                </h2>
                                <div id="collapseThreeTwo" class="accordion-collapse collapse" aria-labelledby="headingThreeTwo" data-bs-parent="#accordionExample-two">
                                    <div class="accordion-body">Yes, volunteering opportunities are available for tree planting and community forestry activities.</div>
                                </div>
                            </div>
                            <a href="#0" class="btn-one mt-35"><span>join with us</span> <i class="fa-solid fa-angles-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Involved area end here -->

    <!-- Testimonial area start here -->
    <section class="testimonial pt-130 pb-130 sub-bg">
        <div class="container">
            <div class="testimonial__head-wrp mb-65">
                <div class="section-header m-0">
                    <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> our client’s feedback</h5>
                    <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">foresty loves people</h2>
                </div>
                <div class="dot-wrp wow fadeInUp mt-5 mt-sm-0" data-wow-duration="1.6s" data-wow-delay=".6s">
                    <div class="dot testimonial__dot"></div>
                </div>
            </div>
            <div class="swiper testimonial__slider">
                <div class="swiper-wrapper">
                    @php $testimonials = [1,2,1]; @endphp
                    @foreach($testimonials as $t)
                        <div class="swiper-slide">
                            <div class="testimonial__item">
                                <div class="testimonial__item-head">
                                    <div class="testimonial__item-head-info">
                                        <div class="testimonial__item-head-info-image"><img src="{{ asset('assets/images/testimonial/'.sprintf('%02d',$t).'.png') }}" alt="image"></div>
                                        <div class="testimonial__item-head-info-con"><h3>{{ $t==1?'Kenneth S. Fisher':'Jennifer R. Tanaka' }}</h3><span>Marketing Manager</span></div>
                                    </div>
                                    <i class="fa-solid fa-quote-right"></i>
                                </div>
                                <div class="testimonial__item-content"><p>posuere luctus orci. Donec vitae mattis quam, vitae tempor arcu. Aenean non odio porttitor, convallis erat sit amet, facilisis velit. Nulla ornare convallis</p></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- Testimonial area end here -->

    <!-- Our info area start here -->
    <div class="our-info" data-background="{{ asset('assets/images/bg/our-info.jpg') }}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                    <a href="{{ route('home') }}" class="our-info__logo mb-4 mb-lg-0"><img src="{{ asset('assets/images/logo/logo-light.svg') }}" alt="logo"></a>
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
