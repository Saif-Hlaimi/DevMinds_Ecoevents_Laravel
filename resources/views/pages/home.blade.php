@extends('layouts.app')

@section('title', 'Ecoevents')

@section('content')
    
        @php
            $homeDir = public_path('assets/images/home');
            $homeUrlBase = 'assets/images/home/';
            $files = is_dir($homeDir) ? glob($homeDir.'/*.jpg') : [];

            $images = [];
            foreach ($files as $f) {
                $info = @getimagesize($f);
                if (!$info) continue;
                $w = $info[0] ?? 0; $h = $info[1] ?? 0; if ($w <= 0 || $h <= 0) continue;
                $images[] = [
                    'path' => $f,
                    'url' => asset($homeUrlBase.basename($f)),
                    'name' => basename($f),
                    'w' => $w,
                    'h' => $h,
                    'ratio' => $w / max(1,$h),
                ];
            }

            $land = array_values(array_filter($images, fn($i) => $i['ratio'] >= 1.2));
            $port = array_values(array_filter($images, fn($i) => $i['ratio'] <= 0.9));
            $neutral = array_values(array_filter($images, fn($i) => $i['ratio'] > 0.9 && $i['ratio'] < 1.2));

            usort($land, fn($a,$b)=> $b['w'] <=> $a['w']);
            usort($port, fn($a,$b)=> $b['h'] <=> $a['h']);
            usort($neutral, fn($a,$b)=> $b['w'] <=> $a['w']);

            $used = [];
            $pick = function(array &$src) use (&$used) {
                foreach ($src as $i) {
                    if (!in_array($i['name'], $used, true)) { $used[] = $i['name']; return $i['url']; }
                }
                return null;
            };

            $heroBg = $pick($land) ?? $pick($neutral) ?? asset('assets/images/bg/banner1.jpg');
            $heroSide = $pick($port) ?? $pick($neutral) ?? $pick($land) ?? asset('assets/images/banner/01.jpg');
            $videoBg = $pick($land) ?? $pick($neutral) ?? asset('assets/images/video/01.jpg');

            $projectSlides = [];
            for ($i=0; $i<5; $i++) {
                $next = $pick($land) ?? $pick($neutral);
                if ($next) $projectSlides[] = $next; else break;
            }
            if (count($projectSlides) < 5) {
                for ($i = 1; $i <= 5; $i++) { $projectSlides[] = asset('assets/images/project/'.sprintf('%02d',$i).'.jpg'); }
                $projectSlides = array_slice($projectSlides,0,5);
            }

            $donationImgs = [];
            for ($i=0; $i<3; $i++) {
                $next = $pick($port) ?? $pick($neutral) ?? $pick($land);
                $donationImgs[] = $next ?: asset('assets/images/donation/'.sprintf('%02d',$i+1).'.jpg');
            }

            $involveBg = $pick($land) ?? $pick($neutral) ?? asset('assets/images/bg/involve.jpg');
        @endphp
    <!-- Banner area start here -->
    <section class="banner" data-background="{{ $heroBg }}"> 
        <div data-wow-duration=".6s" data-wow-delay=".8s">
            
        </div>
        <div id="scrollDown" class="banner__scroll-text"><span>SCROLL NOW</span> <span class="banner__scroll-text-line"></span>
        
</div>
        <div class="banner__leaf wow slideInLeft d-none d-md-block" data-wow-duration="1s" data-wow-delay="1s">
            <img src="{{ asset('assets/images/shape/leaf.png') }}" alt="shape">
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xl-4">
                    <div class="banner__image wow fadeInLeft" data-wow-duration="1.2s" data-wow-delay=".2s">
                        <img src="{{ $heroSide }}" alt="image">
                        <div class="banner__image-text">
                        </div>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="banner__content">
                        <h4 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Embrace the Green, Sow the Future</h4> <br> <br>
                        <h1 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">We Are Here to Increase Your modern life With <span class="primary-color">Ecoevents</span></h1> <br> 
                        <div class="row g-4 align-items-center"> <br> <br>
                            <div class="col-md-4"> <br>
                                <a href="{{ route('about') }}" class="btn-one wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".5s"><span>Discover with us</span> <i class="fa-solid fa-angles-right"></i></a>
                            </div>
                   



                            <div class="col-md-8">
                                <div class="banner__content-con wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s">
                                    <img src="{{ asset('assets/images/icon/arrow-long.png') }}" alt="arrow">
                                    <p>Ecology is the scientific study of the relationships between organisms & their environment, including their physical, chemical</p> 
                                    
                                </div>
                                
                            </div> <br> <br> <br> <br>
                                                            <img src="/assets/images/bg/achievement.jpg" alt="" style="width: 500px; height: 250px; margin-top: 80px; margin-left: 350px;">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner area end here -->

    <!-- About area start here -->
    <section class="about pt-130 pb-65">
        <div class="about__leaf fall__animation">
            <img src="{{ asset('assets/images/about/leaf.png') }}" alt="">
        </div>
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-xl-6">
                    <div class="section-header mb-5">
                        <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> ABOUT US</h5>
                        <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">The true supporter of eco-friendliness</h2>
                        <p class="wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s">Tree planting is the act of planting young trees, shrubs, or other woody plants into the ground to establish new forests or enhance existing ones. It is a crucial component of environmental conservation and reforestation efforts aimed</p>
                    </div>
                    <div class="about__info wow fadeInUp" data-wow-duration="1.8s" data-wow-delay=".8s">
                        <div class="about__info-info-con">
                            <ul>
                                <li>
                                <li>
                                    <h4><a href="#0">DevMinds</a></h4>
                                    <span>Founder</span>
                                </li>
                            </ul>
                        </div>
                        <div class="about__info-signature">
                            <img src="{{ asset('assets/images/about/signature.png') }}" alt="icon">
                        </div>
                    </div>
                </div>
                <div class="col-xl-5">
                    <div class="experience-progress-wrapper">
                        <div class="experience-progress pb-4">
                            <div class="experience-title-wrapper d-flex align-items-center justify-content-between">
                                <h5 class="experience-title pb-2">Carbon Offsetting</h5>
                                <span class="exp" style="left:80%">80%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar wow slideInLeft" data-wow-duration=".8s" role="progressbar" style="width: 80%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="experience-progress pb-4">
                            <div class="experience-title-wrapper d-flex justify-content-between align-items-center">
                                <h5 class="experience-title pb-2">Water Conservation</h5>
                                <span style="left:90%">90%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar wow slideInLeft" data-wow-duration=".9s" role="progressbar" style="width: 90%;" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="experience-progress">
                            <div class="experience-title-wrapper d-flex justify-content-between align-items-center">
                                <h5 class="experience-title pb-2">E-Waste Recycling</h5>
                                <span style="left:70%">70%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar wow slideInLeft" data-wow-duration=".7s" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- About area end here -->

    <!-- Video area start here -->
    <section class="video pt-130 pb-130" data-background="{{ $videoBg }}">
        <div class="container">
            <div class="video__text">
                <h2 class="wow bounceIn" data-wow-duration="1.2s" data-wow-delay=".2s">ECOEVENTS</h2>
            </div>
            <div class="video__btn-wrp">
                <div class="video-btn video-pulse">
                    <a class="video-popup secondary-bg" href="https://www.youtube.com/watch?v=Cn4G2lZ_g2I"><i class="fa-solid fa-play"></i></a>
                </div>
            </div>
        </div>
    </section>
    <!-- Video area end here -->

    <!-- Work area start here -->
    <section class="work pt-130 pb-130">
        <div class="container">
            <div class="pb-65 bor-bottom mb-65">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="section-header m-0">
                            <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> HOW WE WORK</h5>
                            <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">We work together for bettering tomorrow</h2>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s">we are an organization engaged in "Tree Planting" activities, therefore you can "Donate Trees. We also join in "Community Forestry | Reforestation" to keep the earth together so that it remains sustainable.</p>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                @php
                    $workItems = [
                        ['icon' => 'work1.svg', 'num' => '01', 'title' => 'Community Forestry'],
                        ['icon' => 'work2.svg', 'num' => '02', 'title' => 'Individuals'],
                        ['icon' => 'work3.svg', 'num' => '03', 'title' => 'Companies'],
                        ['icon' => 'work4.svg', 'num' => '04', 'title' => 'Education'],
                    ];
                @endphp
                @foreach ($workItems as $i => $w)
                    @php $delay = number_format(0.2 + $i * 0.2, 1); @endphp
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 wow fadeInDown" data-wow-duration="1.2s" data-wow-delay=".{{ $delay }}s">
                        <div class="work__item">
                            <div class="work__item-icon">
                                <img src="{{ asset('assets/images/icon/'.$w['icon']) }}" alt="icon">
                                <span>{{ $w['num'] }}</span>
                            </div>
                            <h3><a href="{{ route('services') }}">{{ $w['title'] }}</a></h3>
                            <p>We maintain a busy network of forestry and social development staff along with local facilitators in the areas we work.</p>
                            <a class="work__item-arrow" href="{{ route('services') }}"><i class="fa-solid fa-arrow-right"></i></a>
                            <div class="work__item-leaf">
                                <img src="{{ asset('assets/images/shape/work-leaf.png') }}" alt="">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Work area end here -->

    <!-- Service area start here -->
    <section class="service pb-130">
        <div class="service__shape wow slideInRight d-none d-xl-block" data-wow-duration="1s" data-wow-delay="1s">
            <img src="{{ asset('assets/images/shape/service1.png') }}" alt="shape">
        </div>
        <div class="text-center pb-3">
            <h5 class="primary-color wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img class="me-1" src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> OUR SERVICE</h5>
        </div>
        <div class="marquee-wrapper text-slider">
            <div class="marquee-inner to-left">
                <ul class="marqee-list d-flex">
                    <li class="marquee-item">
                        our service <img src="{{ asset('assets/images/icon/text-slider-leaf.svg') }}" alt="icon"> <span class="stroke-text">what we do</span> <img src="{{ asset('assets/images/icon/text-slider-leaf.svg') }}" alt="icon">
                        our service <img src="{{ asset('assets/images/icon/text-slider-leaf.svg') }}" alt="icon"> <span class="stroke-text">what we do</span> <img src="{{ asset('assets/images/icon/text-slider-leaf.svg') }}" alt="icon">
                        our service <img src="{{ asset('assets/images/icon/text-slider-leaf.svg') }}" alt="icon"> <span class="stroke-text">what we do</span> <img src="{{ asset('assets/images/icon/text-slider-leaf.svg') }}" alt="icon">
                        our service <img src="{{ asset('assets/images/icon/text-slider-leaf.svg') }}" alt="icon"> <span class="stroke-text">what we do</span> <img src="{{ asset('assets/images/icon/text-slider-leaf.svg') }}" alt="icon">
                        our service <img src="{{ asset('assets/images/icon/text-slider-leaf.svg') }}" alt="icon"> <span class="stroke-text">what we do</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="row g-4">
                <div class="col-xl-6">
                    <div class="service__left-item">
                        <div class="accordion" id="accordionExample">
                            @php
                                $acc = [
                                    ['id'=>'One','title'=>'Events organizing','img'=>'01.jpg','open'=>true],
                                    ['id'=>'Two','title'=>'Donating','img'=>'02.jpg'],
                                    ['id'=>'Three','title'=>'Groups discussions','img'=>'03.jpg'],
                                    ['id'=>'Four','title'=>'Community Foresty','img'=>'04.jpg'],
                                ];
                            @endphp
                            @foreach($acc as $i => $a)
                                <div class="accordion-item {{ $i? 'bor-top':'' }} wow fadeInUp" data-wow-duration="{{ number_format(1.2 + $i*0.2,1) }}s" data-wow-delay=".{{ number_format(0.2 + $i*0.2,1) }}s">
                                    <h2 class="accordion-header" id="heading{{ $a['id'] }}">
                                        <button class="accordion-button changeImage {{ $a['open']??false ? '' : 'collapsed' }}" data-image="{{ asset('assets/images/service/'.$a['img']) }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $a['id'] }}" aria-expanded="{{ $a['open']??false ? 'true' : 'false' }}" aria-controls="collapse{{ $a['id'] }}">{{ $a['title'] }}</button>
                                    </h2>
                                    <div id="collapse{{ $a['id'] }}" class="accordion-collapse collapse {{ $a['open']??false ? 'show' : '' }}" aria-labelledby="heading{{ $a['id'] }}" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <p>Tree planting is the act of planting young trees, shrubs, or other woody plants into the ground to establish new forests or enhance existing ones. It is a crucial component of environmental</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 wow fadeInDown" data-wow-duration="1.8s" data-wow-delay=".4s">
                    <div class="image">
                        <img id="myImage" src="{{ asset('assets/images/service/01.jpg') }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Service area end here -->

    <!-- Achievement area start here -->
    <section class="achievement" data-background="{{ asset('assets/images/bg/achievement.jpg') }}">
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

    <!-- Project area start here -->
    <section class="project pt-130 pb-130">
        <div class="project__wrp">
            <div class="project__wrp-shape sway_Y__animation d-none d-md-block">
                <img src="{{ asset('assets/images/shape/project1.png') }}" alt="shape">
            </div>
            <div class="swiper have-bg project-slider d-none d-lg-block">
                <div class="swiper-wrapper">
                    @foreach ($projectSlides as $src)
                        <div class="swiper-slide">
                            <div class="project__image bg-image" style="background-image: url({{ $src }});"></div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-end">
                    <div class="col-lg-6 ps-4 bor-left py-lg-5">
                        <div class="section-header pr-20">
                            <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> OUR case study</h5>
                            <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">Successfully completed projects for our clients</h2>
                            <p class="wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s">we are an organization engaged in "Tree Planting" activities, therefore you can "Donate Trees. We also join in "Community Forestry | Reforestation" to keep <br> the earth together so that it remains sustainable.</p>
                        </div>
                        <div class="arry-btn mb-4 d-block d-lg-none">
                            <button class="arry-prev project-arry-prev"><i class="fa-light fa-chevron-left"></i></button>
                            <button class="ms-3 active arry-next project-arry-next"><i class="fa-light fa-chevron-right"></i></button>
                        </div>
                        <div class="row g-3">
                            <div class="col-sm-4"><div class="wrp"><div class="pegi-number pt-4"></div></div></div>
                            <div class="col-sm-8">
                                <div class="swiper project-slider2">
                                    <div class="swiper-wrapper">
                                            @foreach ($projectSlides as $src)
                                                <div class="swiper-slide">
                                                    <div class="project__image bg-image" style="background-image: url({{ $src }});"></div>
                                                </div>
                                            @endforeach
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="arry-btn mt-65 d-none d-lg-block">
                            <button class="arry-prev project-arry-prev"><i class="fa-light fa-chevron-left"></i></button>
                            <button class="ms-3 active arry-next project-arry-next"><i class="fa-light fa-chevron-right"></i></button>
                        </div>
                        <div class="col-lg-6">
                            <div class="swiper project-slider mt-5 d-block d-lg-none">
                                <div class="swiper-wrapper">
                                    @foreach ($projectSlides as $src)
                                        <div class="swiper-slide"><div class="image"><img src="{{ $src }}" alt="image"></div></div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Project area end here -->

    <!-- Donation area start here -->
    <section class="donation pt-130 pb-130" data-background="{{ asset('assets/images/bg/donation.jpg') }}">
        <div class="container">
            <div class="donation__wrp">
                <div class="donation__head-wrp mb-65">
                    <div class="section-header m-0">
                        <h5 class="text-white wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf-light.svg') }}" alt="image"> OPEN DONATION</h5>
                        <h2 class="text-white wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">Fundraising Causes Need <br> for future</h2>
                    </div>
                    <div class="arry-btn mt-5 mt-lg-0">
                        <button class="arry-prev donation__arry-prev wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s"><i class="fa-light fa-chevron-left"></i></button>
                        <button class="ms-3 active arry-next donation__arry-next wow fadeInUp" data-wow-duration="1.8s" data-wow-delay=".8s"><i class="fa-light fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="swiper donation__slider">
                    <div class="swiper-wrapper">
                                    @for ($i = 1; $i <= 3; $i++)
                            <div class="swiper-slide">
                                <div class="donation__item">
                                                <div class="image mb-30"><img src="{{ $donationImgs[$i-1] }}" alt="image"></div>
                                    <div class="donation__item-progress-wrp">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <h6>Goal $3000</h6><h6>Raised $2000</h6>
                                        </div>
                                        <div class="donation__item-progress-bar"></div>
                                    </div>
                                    <h3><a href="#0">The Mary Donate For Youth Health & plants</a></h3>
                                    <a class="donation__item-arrow" href="#0"><i class="fa-solid fa-arrow-right"></i></a>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Donation area end here -->

    <!-- Involve area start here -->
    <section class="involve pt-130 pb-130">
        <div class="involve__leaf wow slideInLeft d-none d-sm-block" data-wow-duration="1s" data-wow-delay="1s">
            <img src="{{ asset('assets/images/shape/leaf-theme.png') }}" alt="shape">
        </div>
        <div class="involve__leaf2 wow slideInRight d-none d-sm-block" data-wow-duration="1.2s" data-wow-delay="1.2s">
            <img src="{{ asset('assets/images/shape/leaf-theme2.png') }}" alt="shape">
        </div>
        <div class="container">
            <div class="involve__bg pt-130 pb-130" data-background="{{ $involveBg }}">
                <div class="involve__item text-center">
                    <div class="section-header">
                        <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> Get Involved Now</h5>
                        <h2 class="text-white wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">We Have The Power Today To <br> Change Tomorrow</h2>
                    </div>
                    <a href="#0" class="btn-one-light wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s"><span>join with us</span> <i class="fa-solid fa-angles-right"></i></a>
                </div>
            </div>
        </div>
    </section>
    <!-- Involve area end here -->

    <!-- Testimonial area start here -->
    <section class="testimonial pt-130 pb-130 sub-bg">
        <div class="container">
            <div class="testimonial__head-wrp mb-65">
                <div class="section-header m-0">
                    <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> our client’s feedback</h5>
                    <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">Ecoevents loves people</h2>
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

    <!-- Team area start here -->
    <section class="team pt-130">
        <div class="team__shape fall__animation"><img src="{{ asset('assets/images/shape/team.png') }}" alt="shape"></div>
        <div class="container">
            <div class="pb-65 bor-bottom">
                <div class="section-header text-center">
                    <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> OUR TEAM MEMBERS</h5>
                    <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">ECOEVENTS awesome team</h2>
                </div>
                <div class="row g-4">
                    @for ($i = 1; $i <= 4; $i++)
                        <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-duration="{{ number_format(1.2 + ($i-1)*0.2,1) }}s" data-wow-delay=".{{ number_format(0.2 + ($i-1)*0.2,1) }}s">
                            <div class="team__item">
                                <div class="team__item-image">
                                    <img src="{{ asset('assets/images/team/'.sprintf('%02d',$i).'.png') }}" alt="image">
                                    <div class="team__item-image-icon social-icon">
                                        <a href="#0"><i class="fa-brands fa-facebook-f"></i></a>
                                        <a href="#0"><i class="fa-brands fa-twitter"></i></a>
                                        <a href="#0"><i class="fa-brands fa-linkedin-in"></i></a>
                                        <a href="#0"><i class="fa-brands fa-youtube"></i></a>
                                    </div>
                                </div>
                                <h3><a href="#0">{{ ['Feryal Yahyaoui','Saif Hlaimi','Elaa Sboui','Walid Khrouf'][$i-1] }}</a></h3>
                                <span>{{ ['Founder','Forest Officer','Garden Maker','Co - Founder','Yassine Mighri'][$i-1] }}</span>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </section>
    <!-- Team area end here -->

    <!-- Blog area start here -->
    <section class="blog pt-130 pb-130">
        <div class="blog__shape d-none fall__animation d-sm-block"><img src="{{ asset('assets/images/shape/blog.png') }}" alt="shape"></div>
        <div class="container">
            <div class="blog__head-wrp mb-65">
                <div class="section-header m-0">
                    <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s"><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> LATEST NEWS</h5>
                    <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">Get news from Ecoevents</h2>
                </div>
                <a href="{{ url('/blog-3.html') }}" class="btn-one mt-4 mt-md-0 wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s"><span>view all news</span> <i class="fa-solid fa-angles-right"></i></a>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-xl-8 wow fadeInLeft" data-wow-duration="1.2s" data-wow-delay=".2s">
                    <div class="blog__item-left">
                        <div class="swiper blog__slider">
                            <div class="swiper-wrapper">
                                @foreach ([['01','02'],['03','01'],['04','01']] as $pair)
                                    <div class="swiper-slide">
                                        <div class="row g-4">
                                            <div class="col-md-5">
                                                <div class="blog__item-left-content">
                                                    <span class="blog__tag">Environment</span>
                                                    <h3><a href="#0">roup of young volunteers in park. they are planting</a></h3>
                                                    <p>Tree planting is the act of planting young trees, shrubs, or other woody plants into the ground to establish new forests.</p>
                                                    <span class="blog__item-left-content-info">By <strong>Max Trewhitt</strong> 2 weeks ago</span>
                                                </div>
                                            </div>
                                            <div class="col-md-7"><div class="image"><img src="{{ asset('assets/images/blog/'.$pair[0].'.jpg') }}" alt="image"></div></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="blog__item-left-dot-wrp"><div class="dot blog__dot"></div></div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 wow fadeInRight" data-wow-duration="1.4s" data-wow-delay=".4s">
                    <div class="blog__item-right">
                        <a href="#0" class="image d-block"><img src="{{ asset('assets/images/blog/02.jpg') }}" alt="image"></a>
                        <h3><a href="#0">Close up picture of the sapling of the plant is growing</a></h3>
                        <div class="d-flex align-items-center justify-content-between"><span class="blog__tag">Environment</span><span>2 weeks ago</span></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog area end here -->

    <!-- Our info area start here -->
    <div class="our-info" data-background="{{ asset('assets/images/bg/our-info.jpg') }}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                    <a href="{{ route('home') }}" class="our-info__logo mb-4 mb-lg-0">
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
  <!-- Script -->


    <!-- Our info area end here -->
@endsection

