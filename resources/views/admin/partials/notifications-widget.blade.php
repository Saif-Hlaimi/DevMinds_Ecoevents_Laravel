@php
$unreadNotifications = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\NewOrderNotification')->take(5)->get();
$totalUnread = auth()->user()->unreadNotifications()->where('type', 'App\Notifications\NewOrderNotification')->count();
@endphp

<div class="notifications-widget">
    <div class="dropdown">
        <button class="btn btn-outline-primary dropdown-toggle position-relative" type="button" 
                data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-bell"></i>
            @if($totalUnread > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $totalUnread }}
                </span>
            @endif
        </button>
        
        <div class="dropdown-menu dropdown-menu-end notifications-dropdown">
            <div class="dropdown-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Notifications</h6>
                @if($totalUnread > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            Tout marquer comme lu
                        </button>
                    </form>
                @endif
            </div>
            
            @if($unreadNotifications->count() > 0)
                <div class="notifications-list">
                    @foreach($unreadNotifications as $notification)
                        @php
                            $data = $notification->data;
                        @endphp
                        <div class="notification-item" data-notification-id="{{ $notification->id }}">
                            <div class="notification-content">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">
                                            <i class="fas fa-shopping-cart text-primary me-2"></i>
                                            Nouvelle commande
                                        </h6>
                                        <p class="mb-1 text-muted">{{ $data['message'] }}</p>
                                        <small class="text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div class="notification-actions">
                                        <a href="{{ $data['order_url'] }}" class="btn btn-sm btn-primary">
                                            Voir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="my-2">
                        @endif
                    @endforeach
                </div>
                
                @if($totalUnread > 5)
                    <div class="dropdown-footer text-center">
                        <a href="{{ route('dashboard.ecommerce.orders') }}" class="btn btn-outline-primary btn-sm">
                            Voir toutes les commandes
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-3">
                    <i class="fas fa-bell-slash text-muted mb-2" style="font-size: 2rem;"></i>
                    <p class="text-muted mb-0">Aucune nouvelle notification</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.notifications-dropdown {
    min-width: 350px;
    max-height: 400px;
    overflow-y: auto;
}

.notification-item {
    padding: 1rem;
    border-radius: 8px;
    transition: background-color 0.2s ease;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-content h6 {
    font-size: 0.9rem;
    font-weight: 600;
}

.notification-content p {
    font-size: 0.85rem;
    line-height: 1.4;
}

.notification-actions .btn {
    font-size: 0.8rem;
    padding: 0.25rem 0.75rem;
}

.dropdown-header {
    padding: 1rem 1rem 0.5rem 1rem;
    border-bottom: 1px solid #dee2e6;
}

.dropdown-footer {
    padding: 0.5rem 1rem 1rem 1rem;
    border-top: 1px solid #dee2e6;
}

.notifications-list {
    max-height: 300px;
    overflow-y: auto;
}
</style>

<script>
// Auto-refresh des notifications toutes les 30 secondes
setInterval(function() {
    // Optionnel: recharger les notifications via AJAX
    // fetch('/admin/notifications/refresh')
    //     .then(response => response.json())
    //     .then(data => {
    //         // Mettre à jour le widget
    //     });
}, 30000);

// Marquer une notification comme lue quand on clique sur "Voir"
document.querySelectorAll('.notification-item a[href*="orders"]').forEach(function(link) {
    link.addEventListener('click', function() {
        const notificationItem = this.closest('.notification-item');
        const notificationId = notificationItem.dataset.notificationId;
        
        // Marquer comme lu via AJAX
        fetch('/notifications/' + notificationId + '/read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    });
});
</script>

