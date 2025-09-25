<header class="header header-one bor-bottom">
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-one__item d-none d-xl-block">
                <div class="d-flex align-items-center">
                    <div class="header-one__logo">
                        <a href="{{ route('home') }}"><img src="{{ asset('assets/images/logo/logo-light.svg') }}" alt="logo"></a>
                    </div>
                    <button id="openButton" class="header-one__dots">
                        <img src="{{ asset('assets/images/header/header-dot.png') }}" alt="dots">
                    </button>
                </div>
            </div>
            <div class="header-one__item w-100">
                <div class="header-wrapper justify-content-center">
                    <div class="logo-menu d-block d-xl-none">
                        <a href="{{ route('home') }}" class="logo">
                            <img src="{{ asset('assets/images/logo/logo-light.svg') }}" alt="logo">
                        </a>
                    </div>
                    <div class="header-bar d-xl-none">
                        <span></span><span></span><span></span>
                    </div>
                    <ul class="main-menu">
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('services') }}">Services</a></li>
                        <li>
                            <a href="#">Pages</a>
                            <ul class="sub-menu">
                                <li><a href="{{ route('projects') }}">Projects</a></li>
                                <li><a href="{{ route('donations') }}">Donations</a></li>
                                <li><a href="{{ route('events') }}">Events</a></li>
                                <li><a href="{{ route('team') }}">Team</a></li>
                                <li><a href="{{ route('shop') }}">Shop</a></li>
                                <li><a href="{{ route('cart') }}">Cart</a></li>
                                <li><a href="{{ route('checkout') }}">Checkout</a></li>
                                <li><a href="{{ route('faq') }}">FAQ</a></li>
                                
                                <li><a href="{{ route('error.page') }}">404 Error</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('blog') }}">Blog</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        @auth
                            <li class="menu-item-has-children profile-pill">
                                <a href="{{ route('profile') }}">
                                    <span class="profile-avatar">{{ strtoupper(mb_substr(auth()->user()->name,0,1)) }}</span>
                                    <span>{{ \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') }}</span>
                                </a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('profile') }}">View profile</a></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 text-start">Logout</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
            <div class="header-one__item d-none d-xl-block">
                <ul class="header-wrapper header-one__info bor-left">
                    <li class="menu-btn">
                        <a href="{{ route('contact') }}"><span>Get a quote</span> <i class="fa-solid fa-angles-right"></i></a>
                    </li>
                    <li class="menu_info bg-image ms-0" data-background="{{ asset('assets/images/header/header-info-bg.png') }}">
                        <i class="fa-solid call_ico fa-phone-volume"></i>
                        <div class="call_info">
                            <span>Call Any Time</span>
                            <a class="d-block p-0" href="tel:+912659302003">+91 2659 302 003</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
<div id="targetElement" class="side_bar slideInRight side_bar_hidden">
    <div class="side_bar_overlay"></div>
    <div class="logo mb-30">
        <img src="{{ asset('assets/images/logo/logo-mix.svg') }}" alt="logo">
    </div>
    <p class="text-justify">The foundation of any road is the subgrade, which provides a stable base for the road layers above.</p>
    <ul class="info py-4 mt-65 bor-top bor-bottom">
        <li><i class="fa-solid primary-color fa-location-dot"></i> <a href="#0">example@example.com</a></li>
        <li class="py-4"><i class="fa-solid primary-color fa-phone-volume"></i> <a href="tel:+912659302003">+91 2659 302 003</a></li>
        <li><i class="fa-solid primary-color fa-paper-plane"></i> <a href="#0">info.company@gmail.com</a></li>
    </ul>
    <div class="social-icon mt-65">
        <a href="#0"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#0"><i class="fa-brands fa-twitter"></i></a>
        <a href="#0"><i class="fa-brands fa-linkedin-in"></i></a>
        <a href="#0"><i class="fa-brands fa-instagram"></i></a>
    </div>
    <button id="closeButton" class="text-white"><i class="fa-solid fa-xmark"></i></button>
</div>
