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
                    <input type="hidden" name="payment_source_id" id="payment_source_id">
                    <h3 class="mb-30">Select payment Amount</h3>
                    <div class="amount-group mb-70">
                        @php
                            $remaining = $donationCause->goal_amount - $donationCause->raised_amount;
                        @endphp
                        <div class="input-box">
                            <span>$</span>
                            <input class="addAmount-value" type="number" name="amount" id="amount" step="0.01" min="0.01" required value="250">
                        </div>
                        <button type="button" class="amount-btn" data-value="50">$50</button>
                        <button type="button" class="amount-btn" data-value="125">$125</button>
                        <button type="button" class="active amount-btn" data-value="250">$250</button>
                        <button type="button" class="amount-btn" data-value="350">$350</button>
                    </div>
                    @error('amount')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                    <h3 class="mb-30">Select payment Method</h3>
                    <div class="payment-btns mb-65">
                        <button type="button" class="payment-btn active" onclick="setPaymentMethod('test')">Test Donation</button>
                        <button type="button" class="payment-btn" onclick="setPaymentMethod('stripe')">Stripe - Credit Card</button>
                        <button type="button" class="payment-btn" onclick="setPaymentMethod('square')">Square - Credit Card</button>
                    </div>

                    <!-- Stripe Elements for Credit Card -->
                    <div id="stripe-card-element" style="display: none; margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;"></div>
                    <div id="card-errors" role="alert" style="color: red; margin-bottom: 10px;"></div>

                  <!-- Square Card Form -->
                    <div id="square-form" style="display: none; margin-bottom: 20px;">
                        <div id="card-container" style="min-height: 80px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                            <div style="text-align: center; padding: 20px; color: #666;">
                                Initializing payment form...
                            </div>
                        </div>
                        <div id="square-errors" style="color: red; margin-top: 10px; min-height: 20px;"></div>
                    </div>

                    <button type="submit" id="submit-btn" class="btn-one mt-50"><span>Donate Now</span> <i class="fa-solid fa-angles-right"></i></button>
                </form>
                @endif

              <script src="https://js.stripe.com/v3/"></script>
<script src="https://sandbox.web.squarecdn.com/v1/square.js"></script>
<script>
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
    const elements = stripe.elements();
    let cardElement;
    const remaining = {{ $remaining }};
    
    // Square variables
    let squarePayments = null;
    let squareCard = null;
    let isSquareCardReady = false;

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const value = parseFloat(this.dataset.value);
                const adjustedValue = Math.min(value, remaining);
                document.getElementById('amount').value = adjustedValue;
                document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });

    async function initializeSquare() {
        const appId = '{{ env("SQUARE_APPLICATION_ID") }}';
        const locationId = '{{ env("SQUARE_LOCATION_ID") }}';
        
        if (!squarePayments) {
            try {
                // Initialize Square payments
                squarePayments = Square.payments(appId, locationId);
                
                // Initialize card
                squareCard = await squarePayments.card();
                
                // Attach card to container
                await squareCard.attach('#card-container');
                
                console.log('Square card form initialized and attached successfully');
                isSquareCardReady = true;
                document.getElementById('square-errors').textContent = '';
                
            } catch (error) {
                console.error('Failed to initialize Square card:', error);
                document.getElementById('square-errors').textContent = 'Failed to initialize payment form. Please try again or choose another payment method.';
                isSquareCardReady = false;
            }
        } else if (squareCard && !isSquareCardReady) {
            // Re-attach if needed
            try {
                await squareCard.attach('#card-container');
                isSquareCardReady = true;
                document.getElementById('square-errors').textContent = '';
            } catch (error) {
                console.error('Failed to re-attach Square card:', error);
                isSquareCardReady = false;
            }
        }
    }

    function setPaymentMethod(value) {
        document.getElementById('payment_method').value = value;
        document.querySelectorAll('.payment-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');

        const stripeElementDiv = document.getElementById('stripe-card-element');
        const cardErrors = document.getElementById('card-errors');
        const squareForm = document.getElementById('square-form');
        const squareErrors = document.getElementById('square-errors');
        const submitBtn = document.getElementById('submit-btn');

        // Reset all displays
        stripeElementDiv.style.display = 'none';
        cardErrors.style.display = 'none';
        squareForm.style.display = 'none';
        cardErrors.textContent = '';
        squareErrors.textContent = '';
        isSquareCardReady = false;

        if (value === 'stripe') {
            stripeElementDiv.style.display = 'block';
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
            
        } else if (value === 'square') {
            squareForm.style.display = 'block';
            
            // Clean up Stripe if it exists
            if (cardElement) {
                cardElement.destroy();
                cardElement = null;
            }
            
            // Initialize Square with delay to ensure DOM is ready
            setTimeout(() => {
                initializeSquare();
            }, 100);
            
            submitBtn.disabled = false;
            
        } else {
            // Test donation - clean up any payment forms
            if (cardElement) {
                cardElement.destroy();
                cardElement = null;
            }
            // Reset Square state
            isSquareCardReady = false;
            submitBtn.disabled = false;
        }
    }

    document.getElementById('donationForm').addEventListener('submit', async function(event) {
        const paymentMethod = document.getElementById('payment_method').value;
        
        if (paymentMethod === 'stripe') {
            event.preventDefault();
            
            if (!cardElement) {
                document.getElementById('card-errors').textContent = 'Card element not initialized. Please try again.';
                return;
            }
            
            try {
                const result = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                });
                
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
            } catch (error) {
                console.error('Stripe error:', error);
                document.getElementById('card-errors').textContent = 'An error occurred. Please try again.';
            }
            
        } else if (paymentMethod === 'square') {
            event.preventDefault();
            
            // Check if Square card is ready
            if (!isSquareCardReady || !squareCard) {
                document.getElementById('square-errors').textContent = 'Payment form is not ready. Please wait a moment and try again.';
                return;
            }
            
            try {
                // Show loading state
                const submitBtn = document.getElementById('submit-btn');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span>Processing...</span> <i class="fa-solid fa-spinner fa-spin"></i>';
                submitBtn.disabled = true;
                
                const result = await squareCard.tokenize();
                
                if (result.status === 'OK') {
                    document.getElementById('payment_source_id').value = result.token;
                    this.submit();
                } else {
                    let errorMessage = 'Payment failed';
                    if (result.errors && result.errors.length > 0) {
                        errorMessage += ': ' + result.errors[0].message;
                    }
                    document.getElementById('square-errors').textContent = errorMessage;
                    
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Square tokenization error:', error);
                document.getElementById('square-errors').textContent = 'An error occurred during payment processing. Please try again.';
                
                // Reset button
                const submitBtn = document.getElementById('submit-btn');
                submitBtn.innerHTML = '<span>Donate Now</span> <i class="fa-solid fa-angles-right"></i>';
                submitBtn.disabled = false;
            }
        }
        // For 'test' method, let the form submit normally
    });

    // Clean up when leaving the page
    window.addEventListener('beforeunload', function() {
        if (squareCard) {
            squareCard.destroy();
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