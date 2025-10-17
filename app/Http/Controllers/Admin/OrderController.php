<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'user']);
        
        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Recherche par nom client ou email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_email', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }
        
        // Filtrage par statut
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        
        $orders = $query->latest()->paginate(20);
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);
        
        // Statistiques pour le dashboard
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('payment_status', 'pending')->count(),
            'processing_orders' => Order::where('payment_status', 'processing')->count(),
            'shipped_orders' => Order::where('payment_status', 'shipped')->count(),
            'delivered_orders' => Order::where('payment_status', 'delivered')->count(),
            'cancelled_orders' => Order::where('payment_status', 'cancelled')->count(),
        ];
        
        return view('admin.ecommerce-orders', compact('orders', 'products', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
        ]);
        $order = Order::create($data + ['total' => 0]);
        return back()->with('success', 'Order created');
    }

    public function update(Request $request, Order $order)
    {
        if ($request->expectsJson() || $request->ajax()) {
            $data = $request->validate([
                'admin_notes' => 'nullable|string|max:1000',
                'tracking_number' => 'nullable|string|max:255',
                'shipping_notes' => 'nullable|string|max:1000',
                'delivery_notes' => 'nullable|string|max:1000',
            ]);
        } else {
            $data = $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'nullable|string|max:50',
                'customer_address' => 'nullable|string|max:255',
                'customer_city' => 'nullable|string|max:255',
                'customer_postal_code' => 'nullable|string|max:20',
                'admin_notes' => 'nullable|string|max:1000',
                'tracking_number' => 'nullable|string|max:255',
                'shipping_notes' => 'nullable|string|max:1000',
                'delivery_notes' => 'nullable|string|max:1000',
            ]);
        }

        $order->update($data);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Commande mise à jour avec succès'
            ]);
        }
        
        return back()->with('success', 'Commande mise à jour avec succès');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order deleted');
    }

    public function addItem(Request $request, Order $order)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $product = Product::findOrFail($data['product_id']);
        $item = $order->items()->create([
            'product_id' => $product->id,
            'quantity' => $data['quantity'],
            'price' => $product->price,
        ]);
        $this->recalcTotal($order);
        return back()->with('success', 'Item added');
    }

    public function removeItem(Order $order, OrderItem $item)
    {
        if ($item->order_id !== $order->id) {
            abort(404);
        }
        $item->delete();
        $this->recalcTotal($order);
        return back()->with('success', 'Item removed');
    }

    /**
     * Voir les détails d'une commande
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'user']);
        return view('admin.order-details', compact('order'));
    }

    /**
     * Actions rapides pour une commande
     */
    public function actions(Order $order)
    {
        $order->load(['items.product']);
        return view('admin.partials.order-actions', compact('order'));
    }

    private function recalcTotal(Order $order): void
    {
        $total = $order->items()->selectRaw('SUM(quantity * price) as sum')->value('sum') ?? 0;
        $order->update(['total' => $total]);
    }

    public function approve(Request $request, Order $order)
    {
        $order->payment_status = 'succeeded';
        $order->save();
        return back()->with('success', 'Commande approuvée');
    }

    public function reject(Request $request, Order $order)
    {
        $order->payment_status = 'cancelled';
        $order->save();
        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->quantity += $item->quantity;
                $product->save();
            }
        }
        return back()->with('success', 'Commande rejetée');
    }

    public function ship(Request $request, Order $order)
    {
        $request->validate(['tracking_number' => 'required|string']);
        $order->payment_status = 'shipped';
        $order->tracking_number = $request->tracking_number;
        $order->save();
        return back()->with('success', 'Commande expédiée');
    }

    public function deliver(Request $request, Order $order)
    {
        $order->payment_status = 'delivered';
        $order->save();
        return back()->with('success', 'Commande livrée');
    }
}