@extends('layouts.app')
@section('title', $donationCause->title . ' - EcoEvents')
@section('content')
    <!-- Page banner area start here -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">{{ $donationCause->title }}</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Home :</a>
                <a href="{{ route('donation-causes.index') }}">Donation Causes :</a>
                <span class="primary-color">{{ $donationCause->title }}</span>
            </div>
        </div>
    </section>
    <!-- Page banner area end here -->

    <!-- Donatin single area start here -->
    <section class="project-single pt-130 pb-130">
        <div class="container">
            <h3 class="mb-30 text-capitalize">Need Help for {{ $donationCause->title }}</h3>
           
            <p class="mt-30 mb-30">Every day, challenges arise that need care, attention, and action. Every contribution, no matter the size, can create meaningful change, offering hope, support, and opportunity where it is needed most. Giving is more than generosity, it is a commitment to making the world a better place. Each act of support has the power to inspire transformation, strengthen communities, and leave a lasting impact, proving that together, collective efforts can truly make a difference.</p>
          <div class="image text-center">
                <img src="{{ $donationCause->image ? asset('storage/' . $donationCause->image) : asset('assets/images/donation/donation-single.jpg') }}" 
                    alt="{{ $donationCause->title }}"
                    style="width: 600px; height: 400px; object-fit: cover; border-radius: 8px;">
                @php
                    $percentage = min(($donationCause->raised_amount / $donationCause->goal_amount) * 100, 100);
                    $fullRadius = ($percentage >= 100) ? 'border-top-right-radius: 10px; border-bottom-right-radius: 10px;' : '';
                @endphp
                 <div class="donation__item-progress-wrp mt-40" style="background-color: #e0e0e0; border-radius: 10px; height: 11px; overflow: hidden; display: flex; align-items: center;">
                    <div class="donation__item-progress-bar" style="width: {{ $percentage }}%; background-color: #4CAF50; height: 22px; transition: width 0.3s ease;"></div>
                </div>
                <div class="d-flex align-items-center justify-content-between mt-10">
                    <h6>Raised ${{ number_format($donationCause->raised_amount, 2) }}</h6>
                    <h6>Goal ${{ number_format($donationCause->goal_amount, 2) }}</h6>
                </div>
            </div>
           
           
            <div class="project-single__testimonial sub-bg mt-40 mb-65">
                <p>{{ $donationCause->description }}</p>
                <i class="fa-solid fa-quote-right"></i>
            </div>

            @auth
                @if($donationCause->raised_amount >= $donationCause->goal_amount)
                    <div class="donation-amount-area sub-bg mt-65 text-center">
                        <h3 class="mb-30">The goal has been reached! Thank you for your support.</h3>
                    </div>
                @else
                <form id="donationForm" action="{{ route('donations.store') }}" method="POST" class="donation-amount-area sub-bg mt-65">
                    @csrf
                    <input type="hidden" name="donation_cause_id" value="{{ $donationCause->id }}">
                    <input type="hidden" name="payment_method" id="payment_method" value="test">
                    <h3 class="mb-30">Select payment Amount</h3>
                    <div class="amount-group mb-70">
                        @php
                            $remaining = $donationCause->goal_amount - $donationCause->raised_amount;
                        @endphp
                        <div class="input-box">
                            <span>$</span>
                            <input class="addAmount-value" type="number" name="amount" id="amount" step="0.01" min="0.01" required value="250">
                        </div>
                    </div>
                    @error('amount')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                    <h3 class="mb-30">Select payment Method</h3>
                    <div class="payment-btns mb-65">
                        <button type="button" class="payment-btn active" onclick="setPaymentMethod('test')">Test Donation</button>
                        <button type="button" class="payment-btn" onclick="setPaymentMethod('stripe')">Stripe - Credit Card</button>
                    </div>

                    <!-- Stripe Elements for Credit Card -->
                    <div id="stripe-card-element" style="display: none; margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></div>
                    <div id="card-errors" role="alert" style="color: red; margin-bottom: 10px;"></div>

                    <button type="submit" id="submit-btn" class="btn-one mt-50"><span>Donate Now</span> <i class="fa-solid fa-angles-right"></i></button>
                </form>
                @endif

                <script src="https://js.stripe.com/v3/"></script>
                <script>
                    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
                    const elements = stripe.elements();
                    let cardElement;

                    function setAmount(button, value, remaining) {
                        const adjustedValue = Math.min(value, remaining);
                        document.getElementById('amount').value = adjustedValue;
                        document.querySelectorAll('.amount-btn').forEach(btn => btn.classList.remove('active'));
                        button.classList.add('active');
                    }

                    function setPaymentMethod(value) {
                        document.getElementById('payment_method').value = value;
                        document.querySelectorAll('.payment-btn').forEach(btn => btn.classList.remove('active'));
                        event.target.classList.add('active');

                        const cardElementDiv = document.getElementById('stripe-card-element');
                        const cardErrors = document.getElementById('card-errors');
                        const submitBtn = document.getElementById('submit-btn');

                        if (value === 'stripe') {
                            cardElementDiv.style.display = 'block';
                            cardErrors.style.display = 'block';
                            if (!cardElement) {
                                cardElement = elements.create('card');
                                cardElement.mount('#stripe-card-element');
                                cardElement.on('change', function(event) {
                                    const displayError = document.getElementById('card-errors');
                                    if (event.error) {
                                        displayError.textContent = event.error.message;
                                    } else {
                                        displayError.textContent = '';
                                    }
                                });
                            }
                            submitBtn.disabled = false;
                        } else {
                            cardElementDiv.style.display = 'none';
                            cardErrors.style.display = 'none';
                            cardErrors.textContent = '';
                            if (cardElement) {
                                cardElement.destroy();
                                cardElement = null;
                            }
                            submitBtn.disabled = false;
                        }
                    }

                    document.getElementById('donationForm').addEventListener('submit', function(event) {
                        const paymentMethod = document.getElementById('payment_method').value;
                        if (paymentMethod === 'stripe') {
                            event.preventDefault();
                            stripe.createPaymentMethod({
                                type: 'card',
                                card: cardElement,
                            }).then(function(result) {
                                if (result.error) {
                                    document.getElementById('card-errors').textContent = result.error.message;
                                } else {
                                    // Add payment_method_id to form and submit
                                    const hiddenInput = document.createElement('input');
                                    hiddenInput.type = 'hidden';
                                    hiddenInput.name = 'payment_method_id';
                                    hiddenInput.value = result.paymentMethod.id;
                                    this.appendChild(hiddenInput);
                                    this.submit();
                                }
                            }.bind(this));
                        }
                    });
                </script>
            @else
            <div class="donation-amount-area sub-bg mt-65">
                <h3 class="mb-30">Please log in to make a donation</h3>
                <a href="{{ route('login') }}" class="btn-one mt-50"><span>Login</span> <i class="fa-solid fa-angles-right"></i></a>
            </div>
            @endauth
        </div>
    </section>
    <!-- Donatin single area end here -->

    <!-- Our info area start here -->
    <div class="our-info" data-background="assets/images/bg/our-info.jpg">
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
                        <a class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s" href="#0"><i
                                class="fa-brands fa-facebook-f"></i></a>
                        <a class="wow fadeInUp" data-wow-duration="1.3s" data-wow-delay=".3s" href="#0"><i
                                class="fa-brands fa-twitter"></i></a>
                        <a class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s" href="#0"><i
                                class="fa-brands fa-linkedin-in"></i></a>
                        <a class="wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".5s" href="#0"><i
                                class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




