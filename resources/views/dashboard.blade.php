@extends('layouts.admin')

@section('title', 'CRM Analytics')

@section('content')
    <div class="container-fluid py-2">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="mb-0">CRM Analytics</h3>
                <div class="text-muted">Overview of engagement and activity</div>
            </div>
        </div>

        <!-- Deuxième ligne de statistiques -->
        {{-- === STATS CARDS === --}}
        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted">Utilisateurs</div>
                        <div class="h3 mb-0">{{ $stats['users'] ?? '-' }}</div>
                        <small class="text-muted">Total registered users</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted">Événements</div>
                        <div class="h3 mb-0">{{ $stats['events'] ?? '-' }}</div>
                        <small class="text-muted">Total</small>
                        <div class="text-muted">Total Events</div>
                        <div class="h3 mb-0">{{ $stats['events'] ?? '-' }}</div>
                        <small class="text-muted">All events created</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted">Dons</div>
                        <div class="h3 mb-0">${{ number_format($stats['donations_sum'] ?? 0, 2) }}</div>
                        <small class="text-muted">Montant total</small>
                        <div class="text-muted">Upcoming Events</div>
                        <div class="h3 mb-0 text-success">{{ $stats['events_upcoming'] ?? 0 }}</div>
                        <small class="text-muted">Future scheduled events</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted">Produits</div>
                        <div class="h3 mb-0">{{ $stats['products'] ?? '-' }}</div>
                        <small class="text-muted">Catalogue</small>
                        <div class="text-muted">Past Events</div>
                        <div class="h3 mb-0 text-secondary">{{ $stats['events_past'] ?? 0 }}</div>
                        <small class="text-muted">Completed events</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commandes récentes -->
        <div class="row g-3 mt-3">
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Commandes Récentes</span>
                            <a href="{{ route('dashboard.ecommerce.orders') }}" class="btn btn-sm btn-outline-primary">
                                Voir toutes
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Client</th>
                                            <th>Total</th>
                                            <th>Statut</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentOrders as $order)
                                            <tr>
                                                <td><strong>#{{ $order->id }}</strong></td>
                                                <td>
                                                    <div>
                                                        <div class="fw-semibold">{{ $order->customer_name }}</div>
                                                        <small class="text-muted">{{ $order->customer_email }}</small>
                                                    </div>
                                                </td>
                                                <td><strong>{{ $order->formatted_total }}</strong></td>
                                                <td>
                                                   
                                                </td>
                                                <td>
                                                    <small>{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                                </td>
                                                <td>
                                                    <a href="{{ route('dashboard.ecommerce.orders.show', $order->id) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        Voir
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart text-muted mb-3" style="font-size: 3rem;"></i>
                                <p class="text-muted mb-0">Aucune commande récente</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Widget des notifications -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <span class="fw-semibold">Notifications</span>
                    </div>
                    <div class="card-body">
                        @include('admin.partials.notifications-widget')
        <div class="row g-3 mt-2">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted">Free Events</div>
                        <div class="h3 mb-0 text-info">{{ $stats['events_free'] ?? 0 }}</div>
                        <small class="text-muted">Open to everyone</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted">Paid Events</div>
                        <div class="h3 mb-0 text-warning">{{ $stats['events_paid'] ?? 0 }}</div>
                        <small class="text-muted">Require payment</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted">Event Revenue</div>
                        <div class="h3 mb-0 text-success">${{ number_format($stats['events_revenue'] ?? 0, 2) }}</div>
                        <small class="text-muted">Estimated total</small>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <div class="text-muted">Donations</div>
                        <div class="h3 mb-0 text-primary">${{ number_format($stats['donations_sum'] ?? 0, 2) }}</div>
                        <small class="text-muted">Total amount collected</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique d'engagement (conservé pour la compatibilité) -->
        <div class="card mt-3 shadow-sm">
        {{-- === ENGAGEMENT CHART === --}}
        <div class="card mt-4 shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Engagement Trend</span>
                    <small class="text-muted">Last 8 weeks</small>
                </div>
            </div>
            <div class="card-body">
                <div id="engagementChart" style="height:360px;"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            var el = document.getElementById('engagementChart');
            if (!el || !window.echarts) return;

            var chart = echarts.init(el);
            chart.setOption({
                tooltip: { trigger: 'axis' },
                grid: { left: 40, right: 20, top: 20, bottom: 40 },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    data: ['W1','W2','W3','W4','W5','W6','W7','W8']
                },
                yAxis: { type: 'value', splitLine: { lineStyle: { color: '#eee' } } },
                series: [{
                    name: 'Engagement',
                    type: 'line',
                    smooth: true,
                    areaStyle: { opacity: 0.15 },
                    itemStyle: { color: '#4b9cff' },
                    data: [120, 142, 138, 160, 171, 168, 182, 194]
                }]
            });

            window.addEventListener('resize', () => chart.resize());
        })();
    </script>
@endpush