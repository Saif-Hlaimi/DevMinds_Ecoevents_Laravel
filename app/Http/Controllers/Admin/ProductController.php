<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use Dompdf\Options;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::latest()->paginate(10);

        return view('admin.ecommerce-products', compact('products'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'caracteristiques' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
        ], [
            'name.required' => 'Le nom du produit est requis.',
            'image.required' => 'Une image est requise.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Les formats acceptés sont : JPEG, PNG, JPG, GIF, WEBP.',
            'image.max' => 'L\'image ne doit pas dépasser 2MB.',
            'quantity.required' => 'La quantité est requise.',
            'quantity.integer' => 'La quantité doit être un entier.',
            'quantity.min' => 'La quantité ne peut pas être négative.',
            'price.required' => 'Le prix est requis.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'discount_price.numeric' => 'Le prix promotionnel doit être un nombre.',
            'discount_price.min' => 'Le prix promotionnel ne peut pas être négatif.',
            'discount_price.lte' => 'Le prix promotionnel doit être inférieur ou égal au prix normal.',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return back()->with('success', 'Produit créé avec succès.');
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'caracteristiques' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:price',
        ], [
            'name.required' => 'Le nom du produit est requis.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Les formats acceptés sont : JPEG, PNG, JPG, GIF, WEBP.',
            'image.max' => 'L\'image ne doit pas dépasser 2MB.',
            'quantity.required' => 'La quantité est requise.',
            'quantity.integer' => 'La quantité doit être un entier.',
            'quantity.min' => 'La quantité ne peut pas être négative.',
            'price.required' => 'Le prix est requis.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'price.min' => 'Le prix ne peut pas être négatif.',
            'discount_price.numeric' => 'Le prix promotionnel doit être un nombre.',
            'discount_price.min' => 'Le prix promotionnel ne peut pas être négatif.',
            'discount_price.lte' => 'Le prix promotionnel doit être inférieur ou égal au prix normal.',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return back()->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        // Supprimer l'image si elle existe
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return back()->with('success', 'Produit supprimé avec succès.');
    }

    /**
     * Export products to PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        $products = Product::latest()->get();

        $totalProducts = $products->count();
        $inStockProducts = $products->where('quantity', '>', 0)->count();
        $outOfStockProducts = $totalProducts - $inStockProducts;
        $totalQuantity = $products->sum('quantity');
        $totalValue = $products->sum(function ($product) {
            return $product->price * $product->quantity;
        });
        $totalPromoValue = $products->sum(function ($product) {
            $price = $product->discount_price ?? $product->price;
            return $price * $product->quantity;
        });
        $averagePrice = $totalProducts > 0 ? $totalValue / $totalProducts : 0;

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $html = $this->generatePdfHtml($products, $totalProducts, $inStockProducts, $outOfStockProducts, $totalQuantity, $totalValue, $totalPromoValue, $averagePrice);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('liste_produits.pdf', ['Attachment' => false]);
    }

    /**
     * Generate HTML content for PDF.
     */
    private function generatePdfHtml($products, $totalProducts, $inStockProducts, $outOfStockProducts, $totalQuantity, $totalValue, $totalPromoValue, $averagePrice)
    {
        $html = '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>Liste des produits</title>
            <style>
                body { 
                    font-family: DejaVu Sans, sans-serif; 
                    font-size: 12px; 
                    margin: 20px;
                }
                .header { 
                    text-align: center; 
                    margin-bottom: 20px; 
                    border-bottom: 2px solid #333; 
                    padding-bottom: 10px;
                }
                .header h1 { 
                    color: #333; 
                    margin: 0; 
                    font-size: 24px;
                }
                .info { 
                    margin-bottom: 20px; 
                    background: #f8f9fa; 
                    padding: 15px; 
                    border-radius: 5px;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-bottom: 20px;
                }
                th, td { 
                    border: 1px solid #ddd; 
                    padding: 8px; 
                    text-align: left;
                }
                th { 
                    background-color: #f8f9fa; 
                    font-weight: bold;
                }
                .badge { 
                    padding: 4px 8px; 
                    border-radius: 4px; 
                    font-size: 10px; 
                    font-weight: bold;
                }
                .badge-success { 
                    background-color: #d4edda; 
                    color: #155724;
                }
                .badge-danger { 
                    background-color: #f8d7da; 
                    color: #721c24;
                }
                .summary { 
                    margin-top: 20px; 
                    padding: 15px; 
                    background-color: #f8f9fa; 
                    border-radius: 5px;
                }
                .generated-date {
                    color: #666;
                    font-size: 10px;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Liste des produits</h1>
                <p class="generated-date">Généré le : ' . date('Y-m-d H:i:s') . '</p>
            </div>

            <div class="info">
                <p><strong>Total des produits :</strong> ' . $totalProducts . '</p>
                <p><strong>En stock :</strong> ' . $inStockProducts . '</p>
                <p><strong>En rupture :</strong> ' . $outOfStockProducts . '</p>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Caractéristiques</th>
                        <th>Prix (€)</th>
                        <th>Prix Promo (€)</th>
                        <th>Quantité</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>';

        foreach ($products as $index => $product) {
            $status = $product->quantity > 0 ? 
                '<span class="badge badge-success">En stock</span>' : 
                '<span class="badge badge-danger">Rupture</span>';
            
            $description = $product->description ? substr($product->description, 0, 50) . (strlen($product->description) > 50 ? '...' : '') : 'N/A';
            $characteristics = $product->caracteristiques ? substr($product->caracteristiques, 0, 50) . (strlen($product->caracteristiques) > 50 ? '...' : '') : 'N/A';
            $discountPrice = $product->discount_price ? number_format($product->discount_price, 2, ',', ' ') . ' €' : 'N/A';
            
            $html .= '
                    <tr>
                        <td>' . ($index + 1) . '</td>
                        <td>' . htmlspecialchars($product->name) . '</td>
                        <td>' . htmlspecialchars($description) . '</td>
                        <td>' . htmlspecialchars($characteristics) . '</td>
                        <td>' . number_format($product->price, 2, ',', ' ') . ' €' . '</td>
                        <td>' . $discountPrice . '</td>
                        <td>' . $product->quantity . '</td>
                        <td>' . $status . '</td>
                    </tr>';
        }

        $html .= '
                </tbody>
            </table>

            <div class="summary">
                <h3>Résumé</h3>
                <p><strong>Valeur totale des produits (sans promo) :</strong> ' . number_format($totalValue, 2, ',', ' ') . ' €' . '</p>
                <p><strong>Valeur totale des produits (avec promo) :</strong> ' . number_format($totalPromoValue, 2, ',', ' ') . ' €' . '</p>
                <p><strong>Quantité totale en inventaire :</strong> ' . $totalQuantity . '</p>
                <p><strong>Prix moyen :</strong> ' . number_format($averagePrice, 2, ',', ' ') . ' €' . '</p>
            </div>

            <div style="margin-top: 30px; text-align: center; color: #666;">
                <p>Généré par le système E-commerce</p>
            </div>
        </body>
        </html>';

        return $html;
    }

    /**
     * Get best selling products statistics
     *
     * @return array
     */
    private function getBestSellingProducts()
    {
        // Récupérer les produits les plus vendus avec leurs quantités vendues
        $bestSelling = OrderItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(quantity * price) as total_revenue')
            )
            ->whereHas('order', function($query) {
                $query->where('payment_status', 'succeeded')
                      ->orWhere('payment_status', 'delivered')
                      ->orWhere('payment_status', 'shipped');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        // Charger les informations des produits
        $bestSelling->load('product');

        // Calculer les statistiques globales
        $totalSold = OrderItem::whereHas('order', function($query) {
            $query->where('payment_status', 'succeeded')
                  ->orWhere('payment_status', 'delivered')
                  ->orWhere('payment_status', 'shipped');
        })->sum('quantity');

        $totalRevenue = OrderItem::whereHas('order', function($query) {
            $query->where('payment_status', 'succeeded')
                  ->orWhere('payment_status', 'delivered')
                  ->orWhere('payment_status', 'shipped');
        })->sum(DB::raw('quantity * price'));

        return [
            'products' => $bestSelling,
            'total_sold' => $totalSold,
            'total_revenue' => $totalRevenue
        ];
    }
}