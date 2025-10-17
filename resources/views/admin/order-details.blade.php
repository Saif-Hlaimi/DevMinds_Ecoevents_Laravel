@extends('layouts.admin')
@section('title', 'Détails de la Commande')
@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Détails de la Commande #{{ $order->id }}</h3>
    <div>
      <a href="{{ route('dashboard.ecommerce.orders') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Retour
      </a>
    </div>
  </div>

  <div class="row">
    <!-- Informations principales -->
    <div class="col-lg-8">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">Informations de la Commande</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6>Client</h6>
              <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
              <p class="mb-1">{{ $order->customer_email }}</p>
              <p class="mb-1">{{ $order->customer_phone }}</p>
              
              <h6 class="mt-3">Adresse de livraison</h6>
              <p class="mb-1">{{ $order->customer_address }}</p>
              <p class="mb-1">{{ $order->customer_city }}, {{ $order->customer_postal_code }}</p>
            </div>
          
          </div>
        </div>
      </div>

      <!-- Articles de la commande -->
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Articles commandés</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-striped">
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
                      @if($item->product->image_path)
                        <img src="{{ asset('storage/' . $item->product->image_path) }}" 
                             alt="{{ $item->product->name }}" 
                             class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                      @endif
                      <div>
                        <strong>{{ $item->product->name }}</strong>
                        @if($item->product->description)
                          <br><small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                        @endif
                      </div>
                    </div>
                  </td>
                  <td>${{ number_format($item->price, 2) }}</td>
                  <td>{{ $item->quantity }}</td>
                  <td><strong>${{ number_format($item->quantity * $item->price, 2) }}</strong></td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="3" class="text-end"><strong>Total:</strong></td>
                  <td><strong class="text-primary">{{ $order->formatted_total }}</strong></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Sidebar - Informations supplémentaires -->
    <div class="col-lg-4">
      <!-- Informations de paiement -->
      <div class="card mb-4">
        <div class="card-header">
          <h6 class="mb-0">Paiement</h6>
        </div>
        <div class="card-body">
          <p><strong>Méthode:</strong><br>
            @switch($order->payment_method)
              @case('cash')
                💵 Paiement à la livraison
                @break
              @case('card')
                💳 Carte bancaire
                @break
              @case('transfer')
                🏦 Virement bancaire
                @break
            @endswitch
          </p>
          <p><strong>Statut paiement:</strong><br>
            <span class="badge {{ $order->payment_status == 'succeeded' ? 'bg-success' : 'bg-warning' }}">
              {{ $order->payment_status }}
            </span>
          </p>
          @if($order->payment_intent_id)
            <p><strong>ID Paiement:</strong><br>
              <small class="text-muted">{{ $order->payment_intent_id }}</small>
            </p>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Plus de styles pour timeline ou statut */
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Plus d'actions liées au statut
});
</script>
@endsection