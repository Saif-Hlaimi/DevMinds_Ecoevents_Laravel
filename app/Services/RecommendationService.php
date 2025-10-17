<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\OrderItem;
use App\Models\ProductView;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

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
        // Cache key (10 minutes) to avoid recomputing for each page load
        $cacheKey = 'reco:u:'.($userId ?: 'guest').':p:'.($currentProductId ?: 'none').':o:'.($orderId ?: 'none');
        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($userId, $currentProductId, $orderId) {
        // Guests: skip personalized data
        if (empty($userId)) {
            $purchasedProducts = '';
            $viewedProducts = '';
        } else {
            // Récupérer l'historique d'achats en évitant les colonnes manquantes (ex. user_id sur orders)
            $purchasedQuery = OrderItem::query();
            if (Schema::hasColumn('orders', 'id')) {
                $purchasedQuery = $purchasedQuery->whereHas('order', function ($query) use ($userId) {
                    // Filtrer par utilisateur si la colonne existe
                    if (Schema::hasColumn('orders', 'user_id')) {
                        $query->where('user_id', $userId);
                    }
                    // Exclure annulées si la colonne existe
                    if (Schema::hasColumn('orders', 'payment_status')) {
                        $query->where('payment_status', '!=', 'cancelled');
                    }
                });
            }
            $purchasedProducts = $purchasedQuery->with('product')->get()->pluck('product.name')->unique()->implode(', ');

            // Récupérer l'historique de vues
            $viewedProducts = ProductView::where('user_id', $userId)
                ->with('product')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->pluck('product.name')
                ->unique()
                ->implode(', ');
        }

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

        // Réduire la taille du catalogue pour la requête et accélérer
        $catalogSample = Product::where('quantity', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(150)
            ->pluck('name')
            ->implode(', ');

        // Préparer le prompt pour Gemini
        $prompt = "Basé sur l'historique d'achats : {$purchasedProducts} et les produits consultés : {$viewedProducts}. {$context}"
                . "Recommande 3 à 5 produits similaires ou complémentaires de notre catalogue. "
                . "Catalogue disponible : {$catalogSample}. "
                . "Réponds uniquement avec une liste JSON de noms de produits exacts, sans texte supplémentaire.";

        try {
            // Si l'API key est absente, éviter l'appel réseau
            if (empty($this->apiKey)) {
                throw new \RuntimeException('Missing GEMINI_API_KEY');
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->timeout(4)
            ->connectTimeout(2)
            ->retry(1, 200)
            ->post("{$this->endpoint}/{$this->model}:generateContent?key={$this->apiKey}", [
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
            Log::warning('Recommandations: fallback used. Reason: ' . $e->getMessage());
        }

        // Fallback : recommandations aléatoires
        return Product::inRandomOrder()->where('quantity', '>', 0)->limit(5)->get();
        });
    }
}