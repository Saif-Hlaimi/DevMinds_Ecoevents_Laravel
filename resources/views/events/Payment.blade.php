@extends('layouts.app')

@section('title', 'Payment - ' . $event->title)

@section('content')
<style>
    /* 🌈 Style personnalisé pour la page de paiement */
    body {
        background: url("{{ asset('assets/images/bg/footer-two-bg.jpg') }}") no-repeat center center fixed;
        font-family: "Poppins", sans-serif;
    }

    .payment-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 15px;
    }

    .payment-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        padding: 40px 30px;
        width: 100%;
        max-width: 480px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .payment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
    }

    .payment-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 10px;
    }

    .payment-amount {
        font-size: 1.2rem;
        color: #555;
        margin-bottom: 30px;
    }

    .btn-pay {
        background: linear-gradient(90deg, #3b82f6, #10b981);
        border: none;
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        padding: 12px;
        border-radius: 10px;
        width: 100%;
        transition: 0.3s ease;
    }

    .btn-pay:hover {
        background: linear-gradient(90deg, #2563eb, #059669);
        transform: scale(1.02);
    }

    .secure-note {
        color: #6b7280;
        font-size: 0.9rem;
        margin-top: 20px;
    }

    .lock-icon {
        font-size: 1.2rem;
        color: #10b981;
        margin-right: 5px;
    }

    .back-link {
        display: inline-block;
        margin-top: 20px;
        font-weight: 600;
        color: #3b82f6;
        text-decoration: none;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: #2563eb;
        text-decoration: underline;
    }
</style>

<div class="payment-container">
    <div class="payment-card">
        <h2 class="payment-title">💳 Payment for "{{ $event->title }}"</h2>
        <p class="payment-amount">
            Amount to pay: <strong>{{ number_format($event->price, 2) }} $</strong>
        </p>

        <form action="{{ route('events.processPayment', $event) }}" method="POST">
            @csrf
            <button type="submit" class="btn-pay">
                Pay Securely with Stripe
            </button>
        </form>

        <p class="secure-note">
            <i class="fas fa-lock lock-icon"></i>
            You will be redirected to a secure Stripe checkout page.
        </p>

        <a href="{{ route('events.index') }}" class="back-link">
            ← Back to Events
        </a>
    </div>
</div>

<!-- Optional: Font Awesome for lock icon -->
<script src="https://kit.fontawesome.com/a2e0a2a56a.js" crossorigin="anonymous"></script>
@endsection
