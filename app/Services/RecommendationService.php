<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\OrderItem;
use App\Models\ProductView;
use App\Models\Product;

class RecommendationService
{
    protected $apiKey;
    protected $model;
    protected $endpoint;

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
        $this->model = env('GEMINI_MODEL', 'gemini-1.5-flash');
        $this->endpoint = env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models');
    }

    /**
     * Générer des recommandations basées sur l'historique d'achats et de vues
     *
     * @param int $userId
     * @param int|null $currentProductId Produit actuel pour contextualiser
     * @param int|null $orderId Commande récente pour contextualiser
     * @return \Illuminate\Support\Collection
     */
    public function getRecommendations($userId, $currentProductId = null, $orderId = null)
    {
        // Récupérer l'historique d'achats
        $purchasedProducts = OrderItem::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)->where('payment_status', '!=', 'cancelled');
        })->with('product')->get()->pluck('product.name')->unique()->implode(', ');

        // Récupérer l'historique de vues
        $viewedProducts = ProductView::where('user_id', $userId)
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->pluck('product.name')
            ->unique()
            ->implode(', ');

        // Contextualiser avec le produit actuel ou la commande
        $context = '';
        if ($currentProductId) {
            $currentProduct = Product::find($currentProductId);
            if ($currentProduct) {
                $context = "L'utilisateur regarde actuellement : {$currentProduct->name}. ";
            }
        } elseif ($orderId) {
            $orderItems = OrderItem::where('order_id', $orderId)->with('product')->get()->pluck('product.name')->implode(', ');
            $context = "L'utilisateur a récemment commandé : {$orderItems}. ";
        }

        // Préparer le prompt pour Gemini
        $prompt = "Basé sur l'historique d'achats : {$purchasedProducts} et les produits consultés : {$viewedProducts}. {$context}"
                . "Recommande 3 à 5 produits similaires ou complémentaires de notre catalogue. "
                . "Catalogue disponible : " . Product::pluck('name')->implode(', ') . ". "
                . "Réponds uniquement avec une liste JSON de noms de produits exacts, sans texte supplémentaire.";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->endpoint}/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $generated = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $recommendedNames = json_decode($generated, true) ?? [];

                // Récupérer les produits réels du catalogue
                return Product::whereIn('name', $recommendedNames)->where('quantity', '>', 0)->get();
            }
        } catch (\Exception $e) {
            Log::error('Erreur Gemini API: ' . $e->getMessage());
        }

        // Fallback : recommandations aléatoires
        return Product::inRandomOrder()->where('quantity', '>', 0)->limit(5)->get();
    }
}