<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Contact;
use App\Models\User;
use App\Models\Event;
use App\Models\Donation;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'orders' => Order::count(),
            'pending_orders' => Order::where('payment_status', 'pending')->count(),
            'processing_orders' => Order::where('payment_status', 'processing')->count(),
            'completed_orders' => Order::whereIn('payment_status', ['delivered', 'shipped'])->count(),
            'contacts' => Contact::count(),
            'users' => User::count(),
            'events' => Event::count(),
            'donations_sum' => Donation::sum('amount'),
        ];

        // Commandes récentes pour le dashboard
        $recentOrders = Order::with(['items.product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Notifications récentes
        $recentNotifications = auth()->user()->notifications()
            ->where('type', 'App\Notifications\NewOrderNotification')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentOrders', 'recentNotifications'));
    }
}