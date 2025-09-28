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
            'contacts' => Contact::count(),
            'users' => User::count(),
            'events' => Event::count(),
            'donations_sum' => Donation::sum('amount'),
        ];
        return view('dashboard', compact('stats'));
    }
}
