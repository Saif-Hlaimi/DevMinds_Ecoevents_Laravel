@extends('layouts.app')
@section('title', 'Détail de la commande')

@push('styles')
<style>
.order-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: bold;
    text-transform: uppercase;
    font-size: 0.875rem;
}

.status-pending { background-color: #fff3cd; color: #856404; }
.status-processing { background-color: #cce7ff; color: #004085; }
.status-completed { background-color: #d4edda; color: #155724; }
.status-cancelled { background-color: #f8d7da; color: #721c24; }
.status-shipped { background-color: #d1ecf1; color: #0c5460; }

.payment-status {
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
}

.payment-pending { background-color: #fff3cd; color: #856404; }
.payment-succeeded { background-color: #d4edda; color: #155724; }
.payment-failed { background-color: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<div class="page-banner bg-img bg-img-no-parallax" style="background-image: url('{{ asset('assets/images/bg/page-banner.jpg') }}');">
    <div class="container">
        <div class="page-banner-content">
            <h1 class="page-banner-title">Détail de la commande</h1>
            <div class="breadcrumb-container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Mes commandes</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Commande #{{ $order->id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="order-detail pt-130 pb-130">
    <div class="container">
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

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fa-solid fa-box me-2"></i>Articles de la commande</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produit</th>
                                        <th class="text-center">Quantité</th>
                                        <th class="text-end">Prix unitaire</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($item->product->images->first())
                                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="me-3" 
                                                             style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                             style="width: 60px; height: 60px;">
                                                            <i class="fa-solid fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                        <small class="text-muted">Réf: {{ $item->product->id }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ number_format($item->price, 2, ',', ' ') }} €</td>
                                            <td class="text-end fw-bold">{{ number_format($item->quantity * $item->price, 2, ',', ' ') }} €</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total de la commande:</td>
                                        <td class="text-end fw-bold text-success">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                @if($order->notes)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fa-solid fa-note-sticky me-2"></i>Notes de commande</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Informations de la commande</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Numéro de commande:</strong>
                            <div class="text-muted">#{{ $order->id }}</div>
                        </div>
                        <div class="mb-3">
                            <strong>Date de commande:</strong>
                            <div class="text-muted">{{ $order->created_at->format('d/m/Y à H:i') }}</div>
                        </div>
                        <div class="mb-3">
                            <strong>Statut:</strong>
                            <div>
                                <span class="order-status status-{{ $order->status }}">
                                    @if($order->status === 'pending')
                                        En attente
                                    @elseif($order->status === 'processing')
                                        En traitement
                                    @elseif($order->status === 'completed')
                                        Terminée
                                    @elseif($order->status === 'cancelled')
                                        Annulée
                                    @elseif($order->status === 'shipped')
                                        Expédiée
                                    @else
                                        {{ $order->status }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Statut du paiement:</strong>
                            <div>
                                <span class="payment-status payment-{{ $order->payment_status ?? 'pending' }}">
                                    @if($order->payment_status === 'succeeded')
                                        Payé
                                    @elseif($order->payment_status === 'pending')
                                        En attente
                                    @elseif($order->payment_status === 'failed')
                                        Échoué
                                    @else
                                        {{ $order->payment_status ?? 'Non défini' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <strong>Méthode de paiement:</strong>
                            <div class="text-muted">
                                @if($order->payment_method === 'cash')
                                    <i class="fa-solid fa-money-bill-wave me-2"></i>Paiement à la livraison
                                @elseif($order->payment_method === 'card')
                                    <i class="fa-solid fa-credit-card me-2"></i>Carte bancaire
                                @elseif($order->payment_method === 'transfer')
                                    <i class="fa-solid fa-building-columns me-2"></i>Virement bancaire
                                @else
                                    {{ $order->payment_method }}
                                @endif
                            </div>
                        </div>

                        @if(in_array($order->status, ['pending', 'processing']))
                        <div class="mt-4">
                            <form id="cancel-order-form" action="{{ route('orders.cancel', $order->id) }}" method="POST">
                                @csrf
                                <button type="button" class="btn btn-outline-danger w-100" onclick="confirmCancel()">
                                    <i class="fa-solid fa-times me-2"></i>Annuler la commande
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fa-solid fa-user me-2"></i>Informations client</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Nom:</strong>
                            <div class="text-muted">{{ $order->customer_name }}</div>
                        </div>
                        <div class="mb-3">
                            <strong>Email:</strong>
                            <div class="text-muted">{{ $order->customer_email }}</div>
                        </div>
                        <div class="mb-3">
                            <strong>Téléphone:</strong>
                            <div class="text-muted">{{ $order->customer_phone }}</div>
                        </div>
                        <div class="mb-3">
                            <strong>Adresse:</strong>
                            <div class="text-muted">
                                {{ $order->customer_address }}<br>
                                {{ $order->customer_postal_code }} {{ $order->customer_city }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-primary me-2">
                <i class="fa-solid fa-arrow-left me-2"></i>Retour aux commandes
            </a>
            <a href="{{ route('shop') }}" class="btn btn-primary">
                <i class="fa-solid fa-shopping-bag me-2"></i>Continuer les achats
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmCancel() {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande ? Cette action est irréversible.')) {
        document.getElementById('cancel-order-form').submit();
    }
}

// Gérer la soumission du formulaire d'annulation via AJAX pour une meilleure expérience utilisateur
document.addEventListener('DOMContentLoaded', function() {
    const cancelForm = document.getElementById('cancel-order-form');
    if (cancelForm) {
        cancelForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    alert(data.message || 'Une erreur est survenue lors de l\'annulation.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue lors de l\'annulation.');
            });
        });
    }
});
</script>
@endpush