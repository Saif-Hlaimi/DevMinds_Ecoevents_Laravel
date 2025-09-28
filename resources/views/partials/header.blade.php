<header class="header header-one bor-bottom">
    <div class="header-section">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-one__item d-none d-xl-block">
                <div class="d-flex align-items-center">
                    <div class="header-one__logo"> <br>
                        <a href="{{ route('home') }}"><img src="{{ asset('assets/images/logo/images.png') }}" alt="logo"></a>
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
                            <img src="{{ asset('assets/images/logo/images.png') }}" alt="logo">
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
                                <li><a href="{{ route('events.index') }}">Events</a></li>
                                <li><a href="{{ route('team') }}">Team</a></li>
                                <li><a href="{{ route('shop') }}">Shop</a></li>
                                <li><a href="{{ route('cart') }}">Cart</a></li>
                                <li><a href="{{ route('checkout') }}">Checkout</a></li>
                                <li><a href="{{ route('faq') }}">FAQ</a></li>
                                <li><a href="{{ route('error.page') }}">404 Error</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('groups.index') }}">Groups</a></li>
                        <li><a href="{{ route('events.index') }}">Events</a></li>
                        <li><a href="{{ route('donations') }}">Donations</a></li>
                        <li><a href="{{ route('contact') }}">Contact Us</a></li>
                        @auth
                            <li>
                                <a href="{{ route('notifications.index') }}" class="position-relative">
                                    <i class="fa-regular fa-bell"></i>
                                    @php $count = auth()->user()->unreadNotifications()->count(); @endphp
                                    @if($count)
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $count }}</span>
                                    @endif
                                </a>
                            </li>
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
        <button type="submit" class="dropdown-item text-start w-100">
              Logout
        </button>
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
          
        </div>
    </div>
</header>
<div id="targetElement" class="side_bar slideInRight side_bar_hidden">
    <div class="side_bar_overlay"></div>
    <div class="logo mb-30">
        <img src="{{ asset('assets/images/logo/images.png') }}" alt="logo">
    </div>
    <p class="text-justify">The foundation of any road is the subgrade, which provides a stable base for the road layers above.</p>
    <ul class="info py-4 mt-65 bor-top bor-bottom">
        <li><i class="fa-solid primary-color fa-location-dot"></i> <a href="#0">ecoevents@gmail.com</a></li>
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