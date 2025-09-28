@extends('layouts.app')
@section('title', $product->name)
@section('content')
<!-- Page banner area start here -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">{{ $product->name }}</h2>
        <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
            <a href="{{ route('home') }}">Accueil :</a>
            <a href="{{ route('shop') }}">Boutique :</a>
            <a href="{{ route('products.index') }}">Produits :</a>
            <span class="primary-color">{{ $product->name }}</span>
        </div>
    </div>
</section>
<!-- Page banner area end here -->

<!-- Product detail area start here -->
<div class="shop pt-130 pb-130">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="product-detail-image mb-4">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded" 
                             style="max-height: 500px; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/images/product/product1.png') }}" 
                             alt="{{ $product->name }}" 
                             class="img-fluid rounded" 
                             style="max-height: 500px; object-fit: cover;">
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="product-detail-info">
                    <h1 class="mb-3">{{ $product->name }}</h1>
                    
                    <div class="price-section mb-3">
                        <h2 class="text-primary">${{ number_format($product->price, 2) }}</h2>
                    </div>

                    <div class="stock-section mb-3">
                        @if($product->quantity > 0)
                            <span class="badge bg-success fs-6">En stock ({{ $product->quantity }} disponibles)</span>
                        @else
                            <span class="badge bg-danger fs-6">Rupture de stock</span>
                        @endif
                    </div>

                    @if($product->description)
                        <div class="description-section mb-4">
                            <h4>Description</h4>
                            <p class="text-muted">{{ $product->description }}</p>
                        </div>
                    @endif

                    @if($product->caracteristiques)
                        <div class="features-section mb-4">
                            <h4>Caractéristiques</h4>
                            <ul class="list-unstyled">
                                @foreach($product->caracteristiques_array as $feature)
                                    @if(trim($feature))
                                        <li class="mb-1">
                                            <i class="fa-solid fa-check text-success me-2"></i>
                                            {{ trim($feature) }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="action-buttons mt-4">
                        <div class="d-flex gap-3">
                            @if($product->quantity > 0)
                                <button class="btn btn-primary btn-lg">
                                    <i class="fa-solid fa-cart-shopping me-2"></i>Ajouter au panier
                                </button>
                            @else
                                <button class="btn btn-secondary btn-lg" disabled>
                                    <i class="fa-solid fa-cart-shopping me-2"></i>Produit indisponible
                                </button>
                            @endif
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fa-solid fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin actions -->
        @auth
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Actions administrateur</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">
                                <i class="fa-solid fa-edit me-2"></i>Modifier
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                    <i class="fa-solid fa-trash me-2"></i>Supprimer
                                </button>
                            </form>
                            <a href="{{ route('products.create') }}" class="btn btn-success">
                                <i class="fa-solid fa-plus me-2"></i>Nouveau produit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endauth
    </div>
</div>
<!-- Product detail area end here -->
@endsection