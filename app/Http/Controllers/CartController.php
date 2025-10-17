<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Afficher le contenu du panier
     */
    public function index()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $cartItems = Cart::getItems($userId, $sessionId);
        $total = Cart::getTotal($userId, $sessionId);
        $totalItems = Cart::getTotalItems($userId, $sessionId);

        return view('pages.cart', compact('cartItems', 'total', 'totalItems'));
    }

    /**
     * Ajouter un produit au panier (AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Vérifier le stock
        if ($product->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Quantité insuffisante en stock'
            ], 400);
        }

        $userId = Auth::id();
        $sessionId = session()->getId();

        // Vérifier si le produit est déjà dans le panier
        $existingCartItem = Cart::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('product_id', $request->product_id)->first();

        if ($existingCartItem) {
            // Vérifier si on peut ajouter la quantité demandée
            if (($existingCartItem->quantity + $request->quantity) > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Quantité insuffisante en stock'
                ], 400);
            }

            $existingCartItem->quantity += $request->quantity;
            $existingCartItem->save();
        } else {
            // Créer un nouvel article dans le panier
            Cart::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        $totalItems = Cart::getTotalItems($userId, $sessionId);

        return response()->json([
            'success' => true,
            'message' => 'Produit ajouté au panier',
            'totalItems' => $totalItems
        ]);
    }

    /**
     * Mettre à jour la quantité d'un produit dans le panier
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $userId = Auth::id();
        $sessionId = session()->getId();

        $cartItem = Cart::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé dans le panier'
            ], 404);
        }

        // Vérifier le stock
        if ($request->quantity > $cartItem->product->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Quantité insuffisante en stock'
            ], 400);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        $total = Cart::getTotal($userId, $sessionId);
        $totalItems = Cart::getTotalItems($userId, $sessionId);

        return response()->json([
            'success' => true,
            'message' => 'Quantité mise à jour',
            'total' => $total,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * Supprimer un produit du panier
     */
    public function remove($id)
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $cartItem = Cart::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Article non trouvé dans le panier'
            ], 404);
        }

        $cartItem->delete();

        $total = Cart::getTotal($userId, $sessionId);
        $totalItems = Cart::getTotalItems($userId, $sessionId);

        return response()->json([
            'success' => true,
            'message' => 'Produit supprimé du panier',
            'total' => $total,
            'totalItems' => $totalItems
        ]);
    }

    /**
     * Vider le panier
     */
    public function clear()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        Cart::where(function($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })->delete();

        return response()->json([
            'success' => true,
            'message' => 'Panier vidé'
        ]);
    }

    /**
     * Obtenir le contenu du panier pour AJAX
     */
    public function content()
    {
        $userId = Auth::id();
        $sessionId = session()->getId();

        $cartItems = Cart::getItems($userId, $sessionId);
        $total = Cart::getTotal($userId, $sessionId);
        $totalItems = Cart::getTotalItems($userId, $sessionId);

        return response()->json([
            'success' => true,
            'cartItems' => $cartItems,
            'total' => $total,
            'totalItems' => $totalItems
        ]);
    }
}