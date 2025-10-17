@if($order)
<div class="row g-3">
    <div class="col-12">
        <h6>Actions rapides pour la commande #{{ $order->id }}</h6>
        <p class="text-muted small">Client: {{ $order->customer_name }}</p>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Informations</h6>
                <p class="mb-1"><strong>Total:</strong> {{ $order->formatted_total }}</p>
                <p class="mb-1"><strong>Articles:</strong> {{ $order->total_items }}</p>
                <p class="mb-0"><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="d-grid">
            <a href="{{ route('dashboard.ecommerce.orders.show', $order) }}" class="btn btn-outline-primary">
                <i class="fas fa-eye"></i> Voir les détails complets
            </a>
        </div>
    </div>
</div>
@else
<div class="alert alert-danger">
    Commande non trouvée
</div>
@endif