@extends('layouts.app')
@section('title','Projects')
@section('content')
  <!-- Page banner area start here -->
  <section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
      <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Project 01</h2>
      <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
        <a href="{{ route('home') }}">Home :</a>
        <span class="primary-color">Project 01</span>
      </div>
    </div>
  </section>
  <!-- Page banner area end here -->

  <!-- Project one banner area start here -->
  <section class="project-banner pt-130 pb-130">
    <div class="container">
      <div class="banner-three banner-five">
        <div class="swiper banner-three__slider banner-five__slider">
          <div class="swiper-wrapper">
            @foreach ([1,2,3] as $b)
              <div class="swiper-slide">
                <div class="slide-bg project-banner__bg" data-background="{{ asset('assets/images/project/project-banner'.$b.'.jpg') }}"></div>
                <div class="container">
                  <div class="banner-three__content project-banner__content banner-five__content">
                    <h2 class="text-white" data-animation="fadeInUp" data-delay="1s">Our projects for Ecology <br> awareness</h2>
                    <div class="about-two__right-item" data-animation="fadeInUp" data-delay="1.4s">
                      <div class="about_info d-flex align-items-center pt-65">
                        <a href="{{ route('contact') }}" class="btn-one-light"><span>contact us</span> <i class="fa-solid fa-angles-right"></i></a>
                        <span class="bor-left d-none d-sm-block mx-4"></span>
                        <div class="info d-flex flex-wrap align-items-center">
                          <i class="fa-solid bg-white border-0 fa-phone-volume ring-animation"></i>
                          <div class="about_info_con">
                            <span class="d-block text-capitalize text-white">call any time</span>
                            <a class="text-white" href="tel:+912659302003">+91 2659 302 003</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
        <div class="banner-five__arry-btn">
          <button class="arry-prev mb-15 banner-five__arry-prev"><i class="fa-light fa-chevron-left"></i></button>
          <button class="arry-next banner-five__arry-next"><i class="fa-light fa-chevron-right"></i></button>
        </div>
      </div>
    </div>
  </section>
  <!-- Project one banner area end here -->

  <!-- Project area start here -->
  <section class="project-five">
    <div class="row g-0">
      @php
        $projects = [
          ['img'=>'project1.jpg','title'=>'How to save plains and forests Capitalize on low hanging'],
          ['img'=>'project2.jpg','title'=>'Project: Planting 300 trees in the city, Bring to the table win-win survival'],
          ['img'=>'project3.jpg','title'=>'How to find inspiring solution and movement Leverage agile frameworks'],
          ['img'=>'project4.jpg','title'=>'Seamlessly visualize quality intellectual capital without superior'],
          ['img'=>'project5.jpg','title'=>'Holisticly facilitate stand-alone solutions for customer service'],
          ['img'=>'project6.jpg','title'=>'Objectively cocreate senior e-services via intuitive portals'],
        ];
      @endphp
      @foreach ($projects as $i => $p)
        @if ($i % 2 === 0)
          <div class="col-xxl-3 wow fadeInDown col-lg-6" data-wow-duration="1.2s" data-wow-delay=".2s">
            <div class="project-five__image"><img src="{{ asset('assets/images/project/'.$p['img']) }}" alt="image"></div>
          </div>
          <div class="col-xxl-3 wow fadeInDown col-lg-6" data-wow-duration="1.4s" data-wow-delay=".4s">
            <div class="project-five__item sub-bg">
              <div class="project-five__content bg-white">
                <h3><a href="{{ route('project.single') }}">{{ $p['title'] }}</a></h3>
                <p>We maintain a busy network of forestry and social development staff along with local facilitators in the areas we work.</p>
                <a href="{{ route('project.single') }}" class="btn-two"><span>details project</span> <i class="fa-solid fa-angles-right"></i></a>
              </div>
            </div>
          </div>
        @else
          <div class="col-xxl-3 wow fadeInDown col-lg-6" data-wow-duration="1.6s" data-wow-delay=".6s">
            <div class="project-five__image"><img src="{{ asset('assets/images/project/'.$p['img']) }}" alt="image"></div>
          </div>
          <div class="col-xxl-3 wow fadeInDown col-lg-6" data-wow-duration="1.8s" data-wow-delay=".8s">
            <div class="project-five__item sub-bg">
              <div class="project-five__content bg-white">
                <h3><a href="{{ route('project.single') }}">{{ $p['title'] }}</a></h3>
                <p>We maintain a busy network of forestry and social development staff along with local facilitators in the areas we work.</p>
                <a href="{{ route('project.single') }}" class="btn-two"><span>details project</span> <i class="fa-solid fa-angles-right"></i></a>
              </div>
            </div>
          </div>
        @endif
      @endforeach
    </div>
  </section>
  <!-- Project area end here -->
@endsection

