@extends('layouts.app')
@section('title','Panier')
@section('content')
    <!-- Page banner area start here -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Mon Panier</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Accueil</a>
                <span class="primary-color"> : Panier</span>
            </div>
        </div>
    </section>
    <!-- Page banner area end here -->

    <!-- Cart page area start here -->
    <div class="cart pt-130 pb-130">
        <div class="container">
            @if($cartItems->count() > 0)
                <div class="row">
                    <div class="col-lg-8">
                        <div class="cart-table">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Produit</th>
                                            <th scope="col">Prix</th>
                                            <th scope="col">Quantité</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart-items">
                                        @foreach($cartItems as $item)
                                            <tr data-cart-item-id="{{ $item->id }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="cart-item-image me-3">
                                                            @if($item->product->image_path)
                                                                <img src="{{ asset('storage/' . $item->product->image_path) }}" 
                                                                     alt="{{ $item->product->name }}" 
                                                                     class="img-thumbnail" 
                                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                            @else
                                                                <img src="{{ asset('assets/images/product/product1.png') }}" 
                                                                     alt="{{ $item->product->name }}" 
                                                                     class="img-thumbnail" 
                                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                            <small class="text-muted">
                                                                Stock disponible: {{ $item->product->quantity }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="product-price">{{ $item->product->formatted_price }}</span>
                                                </td>
                                                <td>
                                                    <div class="quantity-controls">
                                                        <input type="number" 
                                                               class="form-control quantity-input" 
                                                               value="{{ $item->quantity }}" 
                                                               min="1" 
                                                               max="{{ $item->product->quantity }}"
                                                               data-cart-item-id="{{ $item->id }}"
                                                               style="width: 80px;">
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="item-total">${{ number_format($item->quantity * $item->product->price, 2) }}</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm remove-item" 
                                                            data-cart-item-id="{{ $item->id }}"
                                                            title="Supprimer du panier">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="cart-actions mt-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <a href="{{ route('shop') }}" class="btn btn-outline-primary">
                                            <i class="fa-solid fa-arrow-left me-2"></i>
                                            Continuer mes achats
                                        </a>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="cart-summary">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Résumé de la commande</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Articles ({{ $totalItems }}):</span>
                                        <span id="subtotal">${{ number_format($total, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Livraison:</span>
                                        <span class="text-success">Gratuite</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-4">
                                        <strong>Total:</strong>
                                        <strong id="total">${{ number_format($total, 2) }}</strong>
                                    </div>
                                    
                                    <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mb-3">
                                        <i class="fa-solid fa-credit-card me-2"></i>
                                        Procéder au paiement
                                    </a>
                                    
                                    <div class="text-center">
                                        <small class="text-muted">
                                            <i class="fa-solid fa-shield-alt me-1"></i>
                                            Paiement sécurisé
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Informations supplémentaires -->
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fa-solid fa-info-circle text-primary me-2"></i>
                                        Informations
                                    </h6>
                                    <ul class="list-unstyled small">
                                        <li class="mb-2">
                                            <i class="fa-solid fa-check text-success me-2"></i>
                                            Livraison gratuite pour toute commande
                                        </li>
                                        <li class="mb-2">
                                            <i class="fa-solid fa-check text-success me-2"></i>
                                            Retour possible sous 30 jours
                                        </li>
                                        <li class="mb-2">
                                            <i class="fa-solid fa-check text-success me-2"></i>
                                            Garantie satisfaction
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center">
                    <i class="fa-solid fa-shopping-cart fa-5x text-muted mb-4"></i>
                    <h4>Votre panier est vide</h4>
                    <p>Commencez à ajouter des produits pour remplir votre panier !</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary mt-3">
                        <i class="fa-solid fa-store me-2"></i>
                        Aller à la boutique
                    </a>
                </div>
            @endif
        </div>
    </div>
    <!-- Cart page area end here -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des changements de quantité
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const newQuantity = parseInt(this.value);
            const cartItemId = this.dataset.cartItemId;
            const maxQuantity = parseInt(this.max);
            
            if (newQuantity < 1 || isNaN(newQuantity)) {
                this.value = 1;
                return;
            }
            
            if (newQuantity > maxQuantity) {
                this.value = maxQuantity;
                alert('Quantité maximale disponible: ' + maxQuantity);
                return;
            }
            
            updateCartItem(cartItemId, newQuantity);
        });
    });
    
    // Gestion de la suppression d'articles
    const removeButtons = document.querySelectorAll('.remove-item');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const cartItemId = this.dataset.cartItemId;
            
            if (confirm('Êtes-vous sûr de vouloir supprimer cet article du panier ?')) {
                removeCartItem(cartItemId);
            }
        });
    });
    
    // Gestion du vider le panier
    const clearCartBtn = document.getElementById('clear-cart');
    
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
                clearCart();
            }
        });
    }
    
    // Fonction pour mettre à jour un article du panier
    function updateCartItem(cartItemId, quantity) {
        fetch(`/cart/update/${cartItemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: quantity
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errData => {
                    throw new Error(errData.message || 'Erreur serveur');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateCartTotals(data.total);
                showNotification('success', data.message);
                
                // Mettre à jour le total de l'article
                const row = document.querySelector(`tr[data-cart-item-id="${cartItemId}"]`);
                const priceElement = row.querySelector('.product-price');
                const price = parseFloat(priceElement.textContent.replace('$', ''));
                const itemTotalElement = row.querySelector('.item-total');
                itemTotalElement.textContent = '$' + (price * quantity).toFixed(2);
            } else {
                showNotification('error', data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('error', error.message || 'Une erreur est survenue');
            location.reload();
        });
    }
    
    // Fonction pour supprimer un article du panier
    function removeCartItem(cartItemId) {
        fetch(`/cart/remove/${cartItemId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errData => {
                    throw new Error(errData.message || 'Erreur serveur');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Supprimer la ligne du tableau
                const row = document.querySelector(`tr[data-cart-item-id="${cartItemId}"]`);
                if (row) {
                    row.remove();
                }
                
                updateCartTotals(data.total);
                showNotification('success', data.message);
                
                // Vérifier si le panier est vide
                if (data.totalItems === 0) {
                    location.reload();
                }
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('error', error.message || 'Une erreur est survenue');
        });
    }
    
    // Fonction pour vider le panier
    function clearCart() {
        fetch('/cart/clear', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errData => {
                    throw new Error(errData.message || 'Erreur serveur');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                setTimeout(() => {
                    location.reload();
                }, 1500);
            } else {
                showNotification('error', data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('error', error.message || 'Une erreur est survenue');
        });
    }
    
    // Fonction pour mettre à jour les totaux
    function updateCartTotals(total) {
        document.getElementById('subtotal').textContent = '$' + total.toFixed(2);
        document.getElementById('total').textContent = '$' + total.toFixed(2);
    }
    
    // Fonction pour afficher les notifications
    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Supprimer automatiquement après 3 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }
});
</script>
@endpush