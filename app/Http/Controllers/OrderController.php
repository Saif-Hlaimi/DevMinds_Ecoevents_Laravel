<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use Dompdf\Dompdf;
use Dompdf\Options;

class OrderController extends Controller
{
    /**
     * Display a list of the user's orders
     */
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())
            ->notCancelled() // Use the scope to exclude cancelled orders
            ->orderBy('created_at', 'desc');

        // Search by order ID
        if ($request->has('search') && !empty($request->search)) {
            $query->where('id', 'like', '%' . $request->search . '%');
        }

        // Filter by payment method
        if ($request->has('payment_method') && !empty($request->payment_method)) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the checkout page
     */
    public function checkout()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $cartItems = Cart::getItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $total = Cart::getTotal($userId, $sessionId);
        $totalItems = Cart::getTotalItems($userId, $sessionId);

        // Verify product availability
        foreach ($cartItems as $cartItem) {
            if ($cartItem->quantity > $cartItem->product->quantity) {
                return redirect()->route('cart.index')->with('error',
                    'Le produit "' . $cartItem->product->name . '" n\'est plus disponible en quantité suffisante. Quantité disponible: ' . $cartItem->product->quantity);
            }
        }

        $stripeIntent = null;
        $stripeKey = config('services.stripe.key');
        $stripeSecret = config('services.stripe.secret');

        if ($total > 0 && $stripeKey && $stripeSecret) {
            try {
                Stripe::setApiKey($stripeSecret);
                $paymentIntent = PaymentIntent::create([
                    'amount' => round($total * 100),
                    'currency' => 'eur',
                    'metadata' => ['user_id' => $userId, 'session_id' => $sessionId],
                    'automatic_payment_methods' => ['enabled' => true],
                ]);
                $stripeIntent = $paymentIntent->client_secret;
            } catch (ApiErrorException $e) {
                Log::error('Erreur Stripe lors de la création du PaymentIntent: ' . $e->getMessage());
                session()->flash('warning', 'Le paiement par carte est temporairement indisponible. Veuillez choisir une autre méthode.');
            }
        } elseif ($total > 0 && (!$stripeKey || !$stripeSecret)) {
            session()->flash('warning', 'La méthode de paiement par carte est désactivée car les clés Stripe ne sont pas configurées.');
        }

        return view('pages.checkout', compact('cartItems', 'total', 'totalItems', 'stripeIntent', 'stripeKey'));
    }

    /**
     * Create a new order
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_address' => 'required|string|max:255',
            'customer_city' => 'required|string|max:255',
            'customer_postal_code' => 'required|string|max:20',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cash,transfer,card',
            'payment_intent_id' => 'nullable|string|max:255',
        ]);

        $userId = Auth::id();
        $sessionId = session()->getId();
        $cartItems = Cart::getItems($userId, $sessionId);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        // Re-verify product availability
        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);
            if (!$product || $cartItem->quantity > $product->quantity) {
                return back()->withInput()->with('error',
                    'Le produit "' . $cartItem->product->name . '" n\'est plus disponible en quantité suffisante. Quantité disponible: ' . ($product ? $product->quantity : 0));
            }
        }

        $total = Cart::getTotal($userId, $sessionId);
        $paymentStatus = 'pending';

        DB::beginTransaction();

        try {
            if ($data['payment_method'] === 'card') {
                if (!$request->filled('payment_intent_id')) {
                    DB::rollback();
                    return back()->withInput()->with('error', 'Erreur de paiement : L\'identifiant de l\'intention de paiement est manquant.');
                }

                Stripe::setApiKey(config('services.stripe.secret'));
                $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

                if (in_array($paymentIntent->status, ['succeeded', 'requires_capture'])) {
                    $paymentStatus = 'succeeded';
                }
            }

            $order = Order::create([
                'user_id' => $userId,
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'customer_address' => $data['customer_address'],
                'customer_city' => $data['customer_city'],
                'customer_postal_code' => $data['customer_postal_code'],
                'payment_method' => $data['payment_method'],
                'notes' => $data['notes'],
                'total' => $total,
                'payment_intent_id' => $data['payment_intent_id'],
                'payment_status' => $paymentStatus,
            ]);

            foreach ($cartItems as $cartItem) {
                $order->items()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                ]);
                $product = Product::find($cartItem->product_id);
                $product->quantity -= $cartItem->quantity;
                $product->save();
            }

            Cart::clear($userId, $sessionId);

            DB::commit();

            $user = User::find($userId);
            $user->notify(new NewOrderNotification($order));

            return redirect()->route('orders.show', $order->id)->with('success', 'Commande créée avec succès!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la création de la commande: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    /**
     * Display order details
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Display order receipt
     */
    public function receipt(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        $order->load('items.product');

        return view('orders.receipt', compact('order'));
    }

    /**
     * Download order receipt as PDF
     */
    public function downloadReceipt(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé.');
        }

        $order->load('items.product');

        // Configuration de Dompdf
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);

        // Récupérer le contenu HTML de la vue
        $html = view('orders.receipt-pdf', compact('order'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = "receipt-commande-{$order->id}-" . now()->format('d-m-Y') . '.pdf';

        return $dompdf->stream($filename);
    }

    /**
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id() || !in_array($order->payment_status, ['pending', 'unpaid'])) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible d\'annuler cette commande.'
            ], 403);
        }

        DB::beginTransaction();

        try {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->quantity += $item->quantity;
                    $product->save();
                }
            }

            $order->update(['payment_status' => 'cancelled']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Commande annulée avec succès.'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur annulation commande: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de l\'annulation.'
            ], 500);
        }
    }
}