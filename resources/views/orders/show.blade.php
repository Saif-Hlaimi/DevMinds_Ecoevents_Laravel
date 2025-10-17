@extends('layouts.app')
@section('title', 'Détails de la commande')

@push('styles')
<link href="{{ asset('css/orders.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Banner section for the order details page -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Détails de la commande</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('orders.index') }}">Mes commandes</a>
                <span class="primary-color"> : Commande #{{ $order->id }}</span>
            </div>
        </div>
    </section>

    <!-- Main content for order details -->
    <div class="order-details pt-130 pb-130">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Order information card -->
                    <div class="order-info card mb-4">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h5 class="mb-0">Commande #{{ $order->id }}</h5>
                                    <small class="text-muted">
                                        Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                                    </small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <span class="badge {{ $order->status_class }}">{{ $order->status_label }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Informations de livraison</h6>
                                    <div class="text-muted">
                                        <div class="mb-2"><strong>{{ $order->customer_name }}</strong></div>
                                        <div class="mb-2">{{ $order->customer_email }}</div>
                                        @if($order->customer_phone)
                                            <div class="mb-2">{{ $order->customer_phone }}</div>
                                        @endif
                                        <div class="mb-2">{{ $order->customer_address }}</div>
                                        <div>{{ $order->customer_city }}, {{ $order->customer_postal_code }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Informations de paiement</h6>
                                    <div class="text-muted">
                                        <div class="mb-2">
                                            <strong>Méthode:</strong>
                                            @switch($order->payment_method)
                                                @case('cash')
                                                    Paiement à la livraison
                                                    @break
                                                @case('card')
                                                    Carte bancaire
                                                    @break
                                                @case('transfer')
                                                    Virement bancaire
                                                    @break
                                                @default
                                                    {{ ucfirst($order->payment_method) }}
                                            @endswitch
                                        </div>
                                        <div class="mb-2"><strong>Total:</strong> {{ $order->formatted_total }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <h6>Notes de commande</h6>
                                @if($order->notes)
                                    <div class="text-muted mb-3">{{ $order->notes }}</div>
                                @endif
                                <!-- Form to add or edit notes -->
                                <form id="commentForm" class="mt-3">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">
                                            {{ $order->notes ? 'Modifier les notes' : 'Ajouter des notes' }}
                                        </label>
                                        <textarea class="form-control" id="comment" name="comment" rows="3"
                                                  placeholder="Ajoutez des notes ou commentaires pour cette commande...">{{ $order->notes }}</textarea>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <i class="fa-solid fa-save me-2"></i>
                                        {{ $order->notes ? 'Modifier' : 'Ajouter' }} les notes
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Ordered items card -->
                    <div class="order-items card">
                        <div class="card-header">
                            <h5 class="mb-0">Articles commandés</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Prix unitaire</th>
                                            <th>Quantité</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3">
                                                            @if($item->product->image_path)
                                                                <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                                                     alt="{{ $item->product->name }}"
                                                                     class="img-thumbnail"
                                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                                            @else
                                                                <img src="{{ asset('assets/images/product/product1.png') }}"
                                                                     alt="{{ $item->product->name }}"
                                                                     class="img-thumbnail"
                                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                                            @endif
                                                        </div>
                                                        <div><strong>{{ $item->product->name }}</strong></div>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($item->price, 2, ',', ' ') }} €</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('commentForm');
    
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const comment = formData.get('comment');
            
            if (!comment.trim()) {
                showNotification('error', 'Veuillez saisir un commentaire');
                return;
            }
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Sauvegarde...';
            
            fetch(`/orders/{{ $order->id }}/comment`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ comment: comment })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('success', data.message);
                    const existingComment = document.querySelector('.text-muted.mb-3');
                    if (existingComment) {
                        existingComment.textContent = comment;
                    } else {
                        const commentDisplay = document.createElement('div');
                        commentDisplay.className = 'text-muted mb-3';
                        commentDisplay.textContent = comment;
                        commentForm.parentNode.insertBefore(commentDisplay, commentForm);
                    }
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('error', 'Une erreur est survenue lors de la sauvegarde');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    }
    
    const cancelButton = document.querySelector('.cancel-order');
    if (cancelButton) {
        cancelButton.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
                cancelOrder(orderId, this);
            }
        });
    }
    
    function cancelOrder(orderId, buttonElement) {
        buttonElement.disabled = true;
        buttonElement.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Annulation...';
        
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('success', data.message);
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('error', data.message);
                buttonElement.disabled = false;
                buttonElement.innerHTML = '<i class="fa-solid fa-times me-2"></i>Annuler la commande';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('error', 'Une erreur est survenue lors de l\'annulation');
            buttonElement.disabled = false;
            buttonElement.innerHTML = '<i class="fa-solid fa-times me-2"></i>Annuler la commande';
        });
    }
    
    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(notification);
        setTimeout(() => notification.parentNode?.removeChild(notification), 3000);
    }
});
</script>
@endpush