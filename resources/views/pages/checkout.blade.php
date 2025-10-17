@extends('layouts.app')
@section('title', 'Finaliser la commande')

@push('styles')
<style>
    /* Styles pour les options de paiement */
    .payment-methods-container .form-check {
        margin-bottom: 15px;
        padding: 15px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .payment-methods-container .form-check:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }

    .payment-method-selected {
        border-color: #007bff !important;
        background-color: #e7f1ff !important;
    }

    .payment-methods-container .form-check-label {
        cursor: pointer;
        padding-left: 10px;
        width: 100%;
    }

    /* Style pour désactiver une option */
    .disabled-option {
        opacity: 0.6;
        pointer-events: none;
    }

    /* Styles pour le formulaire Stripe */
    #stripe-payment-section .card {
        border: 2px solid #007bff;
    }

    #card-element {
        padding: 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background: white;
        min-height: 45px;
    }
</style>
@endpush

@section('content')
<div class="page-banner bg-img bg-img-no-parallax" style="background-image: url('{{ asset('assets/images/bg/page-banner.jpg') }}');">
    <div class="container">
        <div class="page-banner-content">
            <h1 class="page-banner-title">Finaliser la commande</h1>
            <div class="breadcrumb-container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Panier</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Finaliser la commande</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="checkout pt-130 pb-130">
    <div class="container">
        <!-- Affichage des messages flash -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Formulaire de commande -->
        <form id="checkout-form" action="{{ route('orders.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fa-solid fa-user me-2"></i>Informations Client</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="customer_name" class="form-label fw-bold">Nom complet *</label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name', Auth::user()->name ?? '') }}" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="customer_email" class="form-label fw-bold">Email *</label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email" value="{{ old('customer_email', Auth::user()->email ?? '') }}" required>
                                @error('customer_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="customer_phone" class="form-label fw-bold">Téléphone *</label>
                                <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="customer_address" class="form-label fw-bold">Adresse *</label>
                                <input type="text" class="form-control @error('customer_address') is-invalid @enderror" id="customer_address" name="customer_address" value="{{ old('customer_address') }}" required>
                                @error('customer_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="customer_city" class="form-label fw-bold">Ville *</label>
                                    <input type="text" class="form-control @error('customer_city') is-invalid @enderror" id="customer_city" name="customer_city" value="{{ old('customer_city') }}" required>
                                    @error('customer_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="customer_postal_code" class="form-label fw-bold">Code Postal *</label>
                                    <input type="text" class="form-control @error('customer_postal_code') is-invalid @enderror" id="customer_postal_code" name="customer_postal_code" value="{{ old('customer_postal_code') }}" required>
                                    @error('customer_postal_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label fw-bold">Notes (optionnel)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fa-solid fa-shopping-cart me-2"></i>Résumé de la commande</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th class="text-end">Quantité</th>
                                            <th class="text-end">Prix</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cartItems as $item)
                                            <tr>
                                                <td>{{ $item->product->name }}</td>
                                                <td class="text-end">{{ $item->quantity }}</td>
                                                <td class="text-end">{{ number_format($item->product->price * $item->quantity, 2) }} €</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2" class="fw-bold">Total</td>
                                            <td class="text-end fw-bold">{{ number_format($total, 2) }} €</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fa-solid fa-credit-card me-2"></i>Méthode de paiement</h4>
                        </div>
                        <div class="card-body payment-methods-container">
                            <div class="form-check" id="cash_option">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_cash" value="cash" {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                <label class="form-check-label" for="payment_cash">Paiement à la livraison</label>
                            </div>
                            <div class="form-check @if(!$stripeIntent) disabled-option @endif" id="card_option">
                                <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" {{ old('payment_method') == 'card' ? 'checked' : '' }} @if(!$stripeIntent) disabled title="Temporairement indisponible" @endif>
                                <label class="form-check-label" for="payment_card">Carte bancaire</label>
                            </div>
                        </div>
                    </div>

                    <div id="stripe-payment-section" style="display: none;">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="card-element"></div>
                                <div id="card-errors" class="text-danger mt-2"></div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="payment-intent-id" name="payment_intent_id">

                    <button type="submit" class="btn btn-success w-100" id="place-order-btn">
                        <span id="submit-text"><i class="fa-solid fa-check me-2"></i>Valider la commande</span>
                        <span id="processing-text" style="display: none;"><i class="fa-solid fa-spinner fa-spin me-2"></i>Traitement en cours...</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
@if($stripeIntent)
<script src="https://js.stripe.com/v3/"></script>
@endif
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    const placeOrderBtn = document.getElementById('place-order-btn');
    const submitText = document.getElementById('submit-text');
    const processingText = document.getElementById('processing-text');
    const stripeSection = document.getElementById('stripe-payment-section');
    let isProcessing = false;

    // 1. Initialisation de Stripe si disponible
    let stripe = null;
    let cardElement = null;
    const paymentIntentClientSecret = '{{ $stripeIntent }}';
    const stripeKey = '{{ $stripeKey }}';

    if (paymentIntentClientSecret && stripeKey) {
        stripe = Stripe(stripeKey);
        const elements = stripe.elements();
        cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#32325d',
                }
            }
        });
    }

    // 2. Gestion de la sélection des méthodes de paiement
    function setupPaymentMethodSelection() {
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');

        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                // Réinitialise les styles de sélection
                document.querySelectorAll('.form-check').forEach(el => {
                    el.classList.remove('payment-method-selected');
                });

                // Applique le style à l'option sélectionnée
                const optionId = this.value + '_option';
                const optionElement = document.getElementById(optionId);
                if (optionElement) {
                    optionElement.classList.add('payment-method-selected');
                }

                // Affiche/masque la section Stripe
                if (this.value === 'card' && cardElement) {
                    stripeSection.style.display = 'block';
                    try {
                        cardElement.mount('#card-element');
                    } catch (e) {
                        // Ignore si le montage échoue (déjà monté)
                    }
                } else {
                    stripeSection.style.display = 'none';
                    if (cardElement) cardElement.unmount();
                }
            });

            // Initialise l'état sélectionné au chargement
            if (method.checked) {
                const optionId = method.value + '_option';
                const optionElement = document.getElementById(optionId);
                if (optionElement) {
                    optionElement.classList.add('payment-method-selected');
                }
            }
        });
    }

    setupPaymentMethodSelection();

    // 4. Monte l'élément carte si la méthode par défaut est "card"
    const paymentCardInput = document.getElementById('payment_card');
    if (paymentCardInput && paymentCardInput.checked && cardElement) {
        stripeSection.style.display = 'block';
        cardElement.mount('#card-element');
    }

    // 5. Gestion de la soumission du formulaire
    checkoutForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (isProcessing) return;

        if (!validateForm()) return;

        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (selectedPaymentMethod && selectedPaymentMethod.value === 'card') {
            await processStripePayment();
        } else {
            submitForm();
        }
    });

    async function processStripePayment() {
        isProcessing = true;
        submitText.style.display = 'none';
        processingText.style.display = 'inline';
        placeOrderBtn.disabled = true;

        try {
            if (!paymentIntentClientSecret || !stripe || !cardElement) {
                showAlert('danger', 'Impossible de traiter le paiement par carte. Le service est indisponible.');
                resetSubmitButton();
                return;
            }

            const customerName = document.getElementById('customer_name').value;
            const customerEmail = document.getElementById('customer_email').value;

            const { paymentIntent, error } = await stripe.confirmCardPayment(paymentIntentClientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: customerName,
                        email: customerEmail,
                    }
                }
            });

            if (error) {
                document.getElementById('card-errors').textContent = error.message;
                showAlert('danger', 'Erreur de paiement : ' + error.message);
                resetSubmitButton();
                return;
            }

            if (paymentIntent.status === 'succeeded' || paymentIntent.status === 'requires_capture') {
                document.getElementById('payment-intent-id').value = paymentIntent.id;
                submitForm();
            } else {
                showAlert('warning', `Paiement non finalisé. Statut: ${paymentIntent.status}. Vérifiez vos informations.`);
                resetSubmitButton();
            }

        } catch (error) {
            console.error('Erreur Stripe:', error);
            showAlert('danger', 'Erreur lors du paiement: ' + (error.message || 'Vérifiez vos informations et réessayez.'));
            resetSubmitButton();
        }
    }

    function submitForm() {
        checkoutForm.submit();
    }

    function resetSubmitButton() {
        isProcessing = false;
        submitText.style.display = 'inline';
        processingText.style.display = 'none';
        placeOrderBtn.disabled = false;
    }

    function validateForm() {
        const requiredFields = checkoutForm.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            field.classList.remove('is-invalid');
            const feedback = field.parentNode.querySelector('.invalid-feedback');
            if (feedback) feedback.remove();

            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                const errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                errorDiv.textContent = 'Ce champ est obligatoire';
                field.parentNode.appendChild(errorDiv);
            }
        });

        const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedPaymentMethod) {
            isValid = false;
            showAlert('warning', 'Veuillez sélectionner une méthode de paiement.');
        }

        if (!isValid) {
            showAlert('warning', 'Remplissez tous les champs obligatoires (*).');
        }

        return isValid;
    }

    function showAlert(type, message) {
        const alerts = document.querySelectorAll('.alert.alert-dismissible');
        alerts.forEach(alert => alert.remove());

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        const container = document.querySelector('.container');
        container.insertBefore(alertDiv, document.getElementById('checkout-form'));

        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => alertDiv.remove(), 8000);
    }
});
</script>
@endpush