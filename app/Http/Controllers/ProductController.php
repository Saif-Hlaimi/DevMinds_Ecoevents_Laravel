<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CommentProd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\RecommendationService;
use App\Models\ProductView;

class ProductController extends Controller
{
    protected $recommendationService;

    /**
     * Inject the RecommendationService dependency.
     */
    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Display the shop page with a list of available products.
     * Filters by stock and allows search and sorting.
     */
    public function shop(Request $request)
    {
        $query = Product::where('quantity', '>', 0); // Only show products in stock

        // Filter products by search term if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        // Sort products based on the selected option
        $sort = $request->get('sort', 'newest'); // Default to 'newest' if no sort provided
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'quantity_asc':
                $query->orderBy('quantity', 'asc');
                break;
            case 'quantity_desc':
                $query->orderBy('quantity', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Get recommendations for authenticated users
        $recommendations = collect();
        if (Auth::check()) {
            $recommendations = $this->recommendationService->getRecommendations(Auth::id());
        }

        return view('pages.shop', compact('products', 'recommendations'));
    }

    /**
     * Display the product list for administration.
     * Allows search and sorting for admin users.
     */
    public function index(Request $request)
    {
        $query = Product::query();

        // Filter products by search term if provided
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        // Sort products based on the selected option
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'quantity_asc':
                $query->orderBy('quantity', 'asc');
                break;
            case 'quantity_desc':
                $query->orderBy('quantity', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        return view('products.index', compact('products'));
    }

    /**
     * Display a specific product with its details and comments.
     */
    public function show(Product $product)
    {
        // Load comments with their associated users
        $product->load(['commentProds.user']);

        // Track product view if user is authenticated
        if (Auth::check()) {
            ProductView::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
        }

        // Get recommendations based on the product and user history
        $userId = Auth::check() ? Auth::id() : null;
        $recommendations = $this->recommendationService->getRecommendations($userId, $product->id);

        return view('products.show', compact('product', 'recommendations'));
    }

    /**
     * Store a new comment for a specific product.
     */
    public function storeComment(Request $request, Product $product)
    {
        // Validate the comment content
        $validated = $request->validate([
            'content' => 'required|string|max:1000|min:3',
        ]);

        // Create the comment
        $comment = CommentProd::create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        // Load the user relationship for the response
        $comment->load('user');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_name' => $comment->user->name,
                    'formatted_date' => $comment->formatted_date,
                ],
                'message' => 'Commentaire ajouté avec succès.',
            ]);
        }

        return back()->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Retrieve AI-based recommendations for the authenticated user.
     */
    public function getRecommendations()
    {
        try {
            $recommendations = $this->recommendationService->getRecommendations(Auth::id());
            return response()->json([
                'success' => true,
                'recommendations' => $recommendations,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des recommandations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Impossible de charger les recommandations',
            ], 500);
        }
    }
}