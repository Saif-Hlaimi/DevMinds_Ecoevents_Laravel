@extends('layouts.app')
@section('title', 'Mes commandes')

@push('styles')
<link href="{{ asset('css/orders.css') }}" rel="stylesheet">
@endpush

@section('content')
    <!-- Banner section for the orders page -->
    <section class="page-banner bg-image pt-130 pb-130">
        <div class="container">
            <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Mes commandes</h2>
            <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
                <a href="{{ route('home') }}">Accueil</a>
                <span class="primary-color"> : Mes commandes</span>
            </div>
        </div>
    </section>

    <!-- Main content for orders list -->
    <div class="orders pt-130 pb-130">
        <div class="container">
            <!-- Search and filter section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="{{ route('orders.index') }}" class="row g-3">
                               
                                <div class="col-md-3">
                                    <label for="payment_method" class="form-label">Méthode de paiement</label>
                                    <select class="form-select" id="payment_method" name="payment_method">
                                        <option value="">Toutes les méthodes</option>
                                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Paiement à la livraison</option>
                                        <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fa-solid fa-search me-2"></i>Filtrer
                                    </button>
                                    @if(request()->has('search') || request()->has('payment_method'))
                                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                            <i class="fa-solid fa-times me-2"></i>Effacer
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <div class="text-muted small">
                                        <strong>{{ $orders->total() }}</strong> commande(s) trouvée(s)
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if($orders->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="orders-list">
                            @foreach($orders as $order)
                                <div class="order-item card mb-4">
                                    <div class="card-header">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h6 class="mb-0">Commande #{{ $order->id }}</h6>
                                                <small class="text-muted">
                                                    {{ $order->created_at->format('d/m/Y à H:i') }}
                                                </small>
                                            </div>
                                            <div class="col-md-6 text-md-end">
                                                <span class="badge bg-{{ $order->payment_method == 'cash' ? 'warning' : ($order->payment_method == 'card' ? 'success' : 'info') }} me-2">
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
                                                </span>
                                                <span class="fw-bold text-primary ms-3">{{ $order->formatted_total }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6 class="mb-3">Articles commandés:</h6>
                                                @foreach($order->items as $item)
                                                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                                        <div class="d-flex align-items-center">
                                                            <div class="me-3">
                                                                @if($item->product->image_path)
                                                                    <img src="{{ asset('storage/' . $item->product->image_path) }}"
                                                                         alt="{{ $item->product->name }}"
                                                                         class="img-thumbnail"
                                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                                @else
                                                                    <img src="{{ asset('assets/images/product/product1.png') }}"
                                                                         alt="{{ $item->product->name }}"
                                                                         class="img-thumbnail"
                                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                                @endif
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                                <small class="text-muted">Quantité: {{ $item->quantity }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="text-end">
                                                            <span class="fw-bold">{{ number_format($item->quantity * $item->price, 2, ',', ' ') }} €</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-md-4">
                                                <div><strong>Adresse:</strong>
                                                    <div>{{ $order->customer_address }}</div>
                                                    <div>{{ $order->customer_city }}, {{ $order->customer_postal_code }}</div>
                                                </div>
                                                @if($order->payment_method)
                                                    <div class="mt-3"><strong>Paiement:</strong>
                                                        <span>
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
                                                        </span>
                                                    </div>
                                                @endif
                                                @if($order->notes)
                                                    <div class="mt-3">
                                                        <h6>Notes:</h6>
                                                        <div class="text-muted small">{{ $order->notes }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fa-solid fa-eye me-2"></i>Voir les détails
                                                </a>
                                                <a href="{{ route('orders.receipt', $order->id) }}" class="btn btn-outline-success btn-sm">
                                                    <i class="fa-solid fa-receipt me-2"></i>Voir le reçu
                                                </a>
                                            </div>
                                            <div class="col-md-6 text-md-end">
                                                @if($order->canBeCancelled())
                                                    <button class="btn btn-outline-danger btn-sm cancel-order" data-order-id="{{ $order->id }}">
                                                        <i class="fa-solid fa-times me-2"></i>Annuler la commande
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($orders->hasPages())
                            <div class="pt-30 bor-top mt-65">
                                @if($orders->onFirstPage())
                                    <span class="blog-pegi disabled">Précédent</span>
                                @else
                                    <a class="blog-pegi" href="{{ $orders->previousPageUrl() }}">Précédent</a>
                                @endif
                                @foreach(range(1, $orders->lastPage()) as $page)
                                    <a class="blog-pegi {{ $page == $orders->currentPage() ? 'active' : '' }}" 
                                       href="{{ $orders->url($page) }}">{{ $page }}</a>
                                @endforeach
                                @if($orders->hasMorePages())
                                    <a class="blog-pegi" href="{{ $orders->nextPageUrl() }}">
                                        <i class="fa-solid blog_pegi_arrow fa-arrow-right-long"></i>
                                    </a>
                                @else
                                    <span class="blog-pegi disabled">
                                        <i class="fa-solid blog_pegi_arrow fa-arrow-right-long"></i>
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="empty-orders-icon mb-4">
                        <i class="fa-solid fa-shopping-bag" style="font-size: 5rem; color: #ddd;"></i>
                    </div>
                    <h3 class="mb-3">Aucune commande trouvée</h3>
                    <p class="text-muted mb-4">
                        @if(request()->has('search') || request()->has('payment_method'))
                            Aucune commande ne correspond à vos critères de recherche. Essayez de modifier vos filtres.
                        @else
                            Vous n'avez pas encore passé de commande. Découvrez nos produits et commencez vos achats.
                        @endif
                    </p>
                    @if(request()->has('search') || request()->has('payment_method'))
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="fa-solid fa-times me-2"></i>Effacer les filtres
                        </a>
                    @endif
                    <a href="{{ route('shop') }}" class="btn btn-primary">
                        <i class="fa-solid fa-shopping-bag me-2"></i>Commencer mes achats
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cancelButtons = document.querySelectorAll('.cancel-order');
    
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
                cancelOrder(orderId, this);
            }
        });
    });
    
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