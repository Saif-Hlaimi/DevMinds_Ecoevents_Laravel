<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->latest()->paginate(10);
        $products = Product::orderBy('name')->get(['id','name','price']);
        return view('admin.ecommerce-orders', compact('orders', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'status' => 'nullable|string|max:50',
        ]);
        $order = Order::create($data + ['total' => 0]);
        return back()->with('success', 'Order created');
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'status' => 'required|string|max:50',
        ]);
        $order->update($data);
        return back()->with('success', 'Order updated');
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

    private function recalcTotal(Order $order): void
    {
        $total = $order->items()->selectRaw('SUM(quantity * price) as sum')->value('sum') ?? 0;
        $order->update(['total' => $total]);
    }
}
