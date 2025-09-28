<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * AFFICHER la liste des produits pour la boutique (shop page)
     */
    public function shop()
    {
        $products = Product::where('quantity', '>', 0) // Seulement les produits en stock
                          ->latest()
                          ->paginate(12);

        return view('pages.shop', compact('products'));
    }

    /**
     * AFFICHER la liste des produits (pour l'administration).
     */
    public function index()
    {
        // Remplacer get() par paginate() pour avoir la pagination
        $products = Product::latest()->paginate(12); // 12 produits par page
        return view('products.index', compact('products'));
    }

    /**
     * AFFICHER le formulaire de création de produit.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * AJOUTER un nouveau produit (avec gestion de l'image).
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'caracteristiques' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $data = $validator->validated();

        // 2. Traitement de l'upload de l'image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        // 3. Création du produit
        Product::create($data);

        // 4. Redirection
        return redirect()->route('products.index')
                         ->with('success', 'Produit créé avec succès.');
    }

    /**
     * AFFICHER un produit spécifique.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * AFFICHER le formulaire de modification.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * MODIFIER le produit (avec gestion de l'image).
     */
    public function update(Request $request, Product $product)
    {
        // 1. Validation des données
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'nullable|string',
            'caracteristiques' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                            ->withErrors($validator)
                            ->withInput();
        }

        $data = $validator->validated();

        // 2. Traitement de l'upload de la nouvelle image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }
            // Stocker la nouvelle image
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        // 3. Mise à jour du produit
        $product->update($data);

        // 4. Redirection
        return redirect()->route('products.index')
                         ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * SUPPRIMER un produit (avec suppression de l'image associée).
     */
    public function destroy(Product $product)
    {
        // 1. Supprimer l'image associée du stockage
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        // 2. Suppression du produit
        $product->delete();

        // 3. Redirection
        return redirect()->route('products.index')
                         ->with('success', 'Produit supprimé avec succès.');
    }
}