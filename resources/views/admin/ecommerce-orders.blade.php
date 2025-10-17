@extends('layouts.admin')
@section('title', 'Ecommerce - Orders')
@section('content')
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Gestion des Commandes</h3>
    <div class="d-flex gap-2">
      <!-- Statistiques rapides -->
      <div class="btn-group">
        <button type="button" class="btn btn-outline-primary btn-sm">
          Total: <span class="badge bg-primary">{{ $stats['total_orders'] }}</span>
        </button>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row g-3">
    <!-- Colonne gauche : Filtres -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">Filtres et Recherche</div>
        <div class="card-body">
          <form method="GET" action="{{ route('dashboard.ecommerce.orders') }}" class="row g-3">
            <div class="col-12">
              <label class="form-label">Recherche</label>
              <input type="text" name="search" class="form-control" value="{{ request('search') }}" 
                     placeholder="ID, nom, email...">
            </div>
            <div class="col-12">
              <label class="form-label">Date de début</label>
              <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-12">
              <label class="form-label">Date de fin</label>
              <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-12 d-flex gap-2">
              <button type="submit" class="btn btn-primary flex-fill">
                <i class="fas fa-search"></i> Filtrer
              </button>
              <a href="{{ route('dashboard.ecommerce.orders') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i>
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Colonne droite : Liste des commandes -->
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Liste des Commandes</h5>
          <span class="badge bg-primary">{{ $orders->count() }} commande(s)</span>
        </div>
        <div class="card-body table-responsive">
          <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Date</th>
                <th>Articles</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              @forelse($orders as $order)
              <tr>
                <td>
                  <strong class="text-primary">#{{ $order->id }}</strong>
                </td>
                <td>
                  <div>
                    <strong>{{ $order->customer_name }}</strong>
                    <br>
                    <small class="text-muted">{{ $order->customer_email }}</small>
                    @if($order->customer_phone)
                    <br>
                    <small class="text-muted">{{ $order->customer_phone }}</small>
                    @endif
                  </div>
                </td>
                <td>
                  <small>{{ $order->created_at->format('d/m/Y') }}</small>
                  <br>
                  <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                </td>
                <td>
                  @if($order->items->isNotEmpty())
                    <ul class="list-unstyled mb-0">
                      @foreach($order->items as $item)
                        <li>
                          <small>
                            {{ $item->product->name }} (x{{ $item->quantity }})
                          </small>
                        </li>
                      @endforeach
                    </ul>
                  @else
                    <span class="text-muted">Aucun article</span>
                  @endif
                </td>
                <td>
                  <strong class="text-success">{{ $order->formatted_total }}</strong>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center py-4">
                  <div class="text-muted">
                    <i class="fas fa-inbox fa-2x mb-3"></i>
                    <p>Aucune commande trouvée</p>
                    <a href="{{ route('dashboard.ecommerce.orders') }}" class="btn btn-primary btn-sm">
                      Actualiser la liste
                    </a>
                  </div>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
          
          <!-- Pagination -->
          @if($orders->hasPages())
          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="text-muted">
              Affichage de {{ $orders->firstItem() }} à {{ $orders->lastItem() }} sur {{ $orders->total() }} commandes
            </div>
            {{ $orders->links() }}
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Auto-dismiss alerts after 5 seconds
  setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    });
  }, 5000);
});
</script>

<style>
.badge {
    font-size: 0.75rem;
}

.table td {
    vertical-align: middle;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    font-weight: 600;
}

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    color: #6c757d;
}
</style>
@endsection