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

        <div class="row g-3">
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted">Users</div>
                        <div class="h3 mb-0">{{ $stats['users'] ?? '-' }}</div>
                        <small class="text-muted">Total</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted">Events</div>
                        <div class="h3 mb-0">{{ $stats['events'] ?? '-' }}</div>
                        <small class="text-muted">Upcoming/Total</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted">Donations</div>
                        <div class="h3 mb-0">${{ number_format($stats['donations_sum'] ?? 0, 2) }}</div>
                        <small class="text-muted">Total amount</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="text-muted">Orders</div>
                        <div class="h3 mb-0">{{ $stats['orders'] ?? '-' }}</div>
                        <small class="text-muted">Ecommerce</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3 shadow-sm">
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
                xAxis: { type: 'category', boundaryGap: false, data: ['W1','W2','W3','W4','W5','W6','W7','W8'] },
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
