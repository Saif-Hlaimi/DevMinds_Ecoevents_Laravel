<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Contact;
use App\Models\User;
use App\Models\Event;
use App\Models\Donation;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'pending_orders' => $hasPS ? Order::where('payment_status', 'pending')->count() : 0,
            'processing_orders' => $hasPS ? Order::where('payment_status', 'processing')->count() : 0,
            'completed_orders' => $hasPS ? Order::whereIn('payment_status', ['delivered', 'shipped'])->count() : 0,
            'contacts' => Contact::count(),
            'users' => User::count(),
            'events' => Event::count(),
            'events_upcoming' => Event::where('date', '>', $now)->count(),
            'events_past' => Event::where('date', '<', $now)->count(),
            'events_free' => Event::where('is_paid', false)->count(),
            'events_paid' => Event::where('is_paid', true)->count(),
            'events_revenue' => Event::where('is_paid', true)->sum('price'),
            'donations_sum' => Donation::sum('amount'),
        ];

        // Commandes récentes pour le dashboard
        $recentOrders = Order::with(['items.product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Notifications récentes
        $recentNotifications = auth()->check()
            ? auth()->user()->notifications()->where('type', 'App\\Notifications\\NewOrderNotification')->take(5)->get()
            : collect();

        return view('dashboard', compact('stats', 'recentOrders', 'recentNotifications'));
        return view('dashboard', compact('stats'));
    }
}