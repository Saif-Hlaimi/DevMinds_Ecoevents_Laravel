<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin')</title>
    <link rel="stylesheet" href="/vendor/fabkin/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/vendor/fabkin/assets/css/icons.min.css">
    <link rel="stylesheet" href="/vendor/fabkin/assets/css/app.min.css">
    @stack('styles')
    <style>
        body{min-height:100vh;}
        .admin-wrapper{display:flex;min-height:100vh;}
        .admin-sidebar{width:240px;background:#0b1727;color:#fff;}
        .admin-sidebar a{color:#c7d1e0;text-decoration:none;display:block;padding:.6rem 1rem;}
        .admin-sidebar a.active,.admin-sidebar a:hover{background:#0f213e;color:#fff;}
        .admin-content{flex:1;}
        .admin-topbar{background:#fff;border-bottom:1px solid #eaecef;}
    </style>
    @vite([])
</head>
<body>
<div class="admin-wrapper">
    <aside class="admin-sidebar py-3">
        <div class="px-3 mb-2 fw-bold">EcoEvents Admin</div>
        <nav>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Analytics</a>
            <a href="{{ route('dashboard.email') }}" class="{{ request()->routeIs('dashboard.email') ? 'active' : '' }}">Email</a>
            <a href="{{ route('dashboard.chat') }}" class="{{ request()->routeIs('dashboard.chat') ? 'active' : '' }}">Chat</a>
            <a href="{{ route('dashboard.calendar') }}" class="{{ request()->routeIs('dashboard.calendar') ? 'active' : '' }}">Calendar</a>
            <a href="{{ route('dashboard.ecommerce.products') }}" class="{{ request()->routeIs('dashboard.ecommerce.*') ? 'active' : '' }}">Ecommerce - Products</a>
            <a href="{{ route('dashboard.ecommerce.orders') }}" class="{{ request()->routeIs('dashboard.ecommerce.orders') ? 'active' : '' }}">Ecommerce - Orders</a>
            <a href="{{ route('dashboard.invoice.detail') }}" class="{{ request()->routeIs('dashboard.invoice.*') ? 'active' : '' }}">Invoice</a>
            <a href="{{ route('dashboard.crm.contacts') }}" class="{{ request()->routeIs('dashboard.crm.*') ? 'active' : '' }}">CRM - Contacts</a>
            <a href="{{ route('dashboard.academy.courses') }}" class="{{ request()->routeIs('dashboard.academy.*') ? 'active' : '' }}">Academy - Courses</a>
            <a href="{{ route('dashboard.cms.blog') }}" class="{{ request()->routeIs('dashboard.cms.*') ? 'active' : '' }}">CMS - Blog</a>
            <hr class="border-secondary opacity-50">
            <div class="px-3 text-uppercase small text-muted">Admin</div>
            <a href="{{ route('dashboard.admin.users') }}" class="{{ request()->routeIs('dashboard.admin.users*') ? 'active' : '' }}">Users</a>
            <a href="{{ route('dashboard.admin.events') }}" class="{{ request()->routeIs('dashboard.admin.events*') ? 'active' : '' }}">Events</a>
            <a href="{{ route('dashboard.admin.donation-causes.donation-causes') }}" class="{{ request()->routeIs('dashboard.admin.donation-causes.donation-causes*') ? 'active' : '' }}">Donation cause</a>
            <a href="{{ route('dashboard.admin.groups') }}" class="{{ request()->routeIs('dashboard.admin.groups*') ? 'active' : '' }}">Groups</a>
        </nav>
    </aside>
    <section class="admin-content d-flex flex-column">
        <div class="admin-topbar px-3 py-2 d-flex align-items-center gap-2">
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('home') }}">← Back</a>
            <div class="ms-auto d-flex align-items-center gap-2">
                @auth
                    <span class="text-muted small">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button class="btn btn-sm btn-outline-dark">Logout</button>
                    </form>
                @else
                    <a class="btn btn-sm btn-primary" href="{{ route('login') }}">Login</a>
                @endauth
            </div>
        </div>
        <main class="flex-1 p-3">
            @yield('content')
        </main>
    </section>
</div>

<script src="/vendor/fabkin/assets/libs/echarts/echarts.common.js"></script>
<script src="/vendor/fabkin/assets/js/app.js"></script>
@stack('scripts')
</body>
</html>
