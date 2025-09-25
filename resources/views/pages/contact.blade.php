@extends('layouts.app')

@section('title', 'Contact Us - EcoEvents')

@section('content')
    <!-- Page banner area start here -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Contact Us</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Home :</a>
                <span class="primary-color">Contact Us</span>
            </div>
        </div>
    </section>
    <!-- Page banner area end here -->

    <!-- Contact form area start here -->
    <section class="contact pt-130 pb-130">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="contact__item">
                        <div class="section-header mb-4">
                            <h5 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">
                                <img src="{{ asset('assets/images/icon/leaf.svg') }}" alt="image"> Contact Info
                            </h5>
                            <h2 class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">Get in touch</h2>
                            <p class="wow fadeInUp" data-wow-duration="1.6s" data-wow-delay=".6s">We’re here to help. Reach out with any questions or inquiries and our team will respond as soon as possible.</p>
                        </div>
                        <ul class="contact__info">
                            <li class="pb-3"><a href="#0"><i class="fa-solid fa-location-dot pe-1 primary-color"></i>901 N Pitt Str., Suite 170 Alexandria, USA</a></li>
                            <li class="pb-3"><a href="tel:+4065550120"><i class="fa-solid fa-phone-volume pe-1 primary-color"></i>(406) 555-0120</a></li>
                            <li><a href="mailto:info@extrem.com"><i class="fa-solid fa-envelope pe-1 primary-color"></i>info@extrem.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-area">
                        <form action="#0" method="post">
                            <div class="row g-3">
                                <div class="col-md-6"><input type="text" placeholder="Your Name"></div>
                                <div class="col-md-6"><input type="email" placeholder="Email Address"></div>
                                <div class="col-md-6"><input type="text" placeholder="Phone Number"></div>
                                <div class="col-md-6"><input type="text" placeholder="Subject"></div>
                                <div class="col-12"><textarea placeholder="Message"></textarea></div>
                                <div class="col-12"><button type="submit" class="btn-one"><span>Send Message</span> <i class="fa-solid fa-angles-right"></i></button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact form area end here -->

    <!-- Contact map area start here -->
    <div class="google-map">
        <iframe class="contact-map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387193.3059445134!2d-74.2598661379975!3d40.697149417741365!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sbd!4v1670395681365!5m2!1sen!2sbd" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <!-- Contact map area end here -->
@endsection
