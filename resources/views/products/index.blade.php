@extends('layouts.app')
@section('title', 'Gestion des produits')
@section('content')
<!-- Page banner area start here -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Gestion des produits</h2>
        <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
            <a href="{{ route('home') }}">Accueil :</a>
            <a href="{{ route('shop') }}">Boutique :</a>
            <span class="primary-color">Gestion des produits</span>
        </div>
    </div>
</section>
<!-- Page banner area end here -->

<!-- Products management area start here -->
<div class="shop pt-130 pb-130">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>Liste des produits</h3>
                 
                </div>
            </div>
        </div>

        <!-- Barre de recherche + tri -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('products.index') }}">
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="text"
                                               name="search"
                                               class="form-control"
                                               placeholder="Rechercher un produit par nom..."
                                               value="{{ request('search') }}">
                                        @if(request('search'))
                                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" type="button">
                                                <i class="fa-solid fa-times"></i>
                                            </a>
                                        @endif
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fa-solid fa-search me-2"></i>Rechercher
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4 mt-3 mt-md-0">
                                    <label for="admin-sort" class="form-label">Trier</label>
                                    <select id="admin-sort" name="sort" class="form-select" onchange="this.form.submit()">
                                        <option value="newest" {{ request('sort','newest')==='newest' ? 'selected' : '' }}>Plus récent</option>
                                        <option value="oldest" {{ request('sort')==='oldest' ? 'selected' : '' }}>Plus ancien</option>
                                        <option value="price_asc" {{ request('sort')==='price_asc' ? 'selected' : '' }}>Prix : bas → élevé</option>
                                        <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected' : '' }}>Prix : élevé → bas</option>
                                        <option value="quantity_asc" {{ request('sort')==='quantity_asc' ? 'selected' : '' }}>Quantité : faible → élevée</option>
                                        <option value="quantity_desc" {{ request('sort')==='quantity_desc' ? 'selected' : '' }}>Quantité : élevée → faible</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(request('search'))
            <div class="alert alert-info mb-4">
                Résultats de recherche pour : "<strong>{{ request('search') }}</strong>"
                <a href="{{ route('products.index') }}" class="float-end btn btn-sm btn-outline-primary">
                    Afficher tous les produits
                </a>
            </div>
        @endif

        <div class="row">
            @forelse ($products as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card product-card h-100">
                        <div class="card-image position-relative">
                            @if($product->image_path)
                                <img src="{{ asset('storage/' . $product->image_path) }}"
                                     class="card-img-top"
                                     alt="{{ $product->name }}"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <img src="{{ asset('assets/images/product/product1.png') }}"
                                     class="card-img-top"
                                     alt="Image par défaut"
                                     style="height: 250px; object-fit: cover;">
                            @endif
                            <div class="position-absolute top-0 end-0 m-2">
                                @if($product->quantity > 0)
                                    <span class="badge bg-success">En stock</span>
                                @else
                                    <span class="badge bg-danger">Rupture</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-primary fw-bold fs-4">{{ $product->formatted_price }}</p>
                            <p class="card-text text-muted">
                                <small>Quantité : {{ $product->quantity }}</small>
                            </p>
                            @if($product->description)
                                <p class="card-text text-muted small">
                                    {{ Str::limit($product->description, 80) }}
                                </p>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('products.show', $product) }}"
                                   class="btn btn-info btn-sm"
                                   title="Voir le produit">
                                    <i class="fa-solid fa-eye"></i> Voir
                                </a>
                              
                              
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="card">
                        <div class="card-body py-5">
                            @if(request('search'))
                                <h5 class="text-muted">Aucun produit trouvé pour "{{ request('search') }}"</h5>
                                <p class="text-muted">Essayez avec d'autres termes de recherche.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                                    Afficher tous les produits
                                </a>
                            @else
                                <h5 class="text-muted">Aucun produit trouvé</h5>
                                <p class="text-muted">Commencez par ajouter votre premier produit.</p>
                                <a href="{{ route('products.create') }}" class="btn btn-primary mt-3">
                                    <i class="fa-solid fa-plus me-2"></i>Ajouter un produit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="row mt-5">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            @if($products->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Précédent</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->previousPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('sort') ? '&sort=' . request('sort') : '' }}">Précédent</a>
                                </li>
                            @endif

                            @foreach(range(1, $products->lastPage()) as $page)
                                <li class="page-item {{ $page == $products->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $products->url($page) }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('sort') ? '&sort=' . request('sort') : '' }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            @if($products->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->nextPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('sort') ? '&sort=' . request('sort') : '' }}">Suivant</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Suivant</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Products management area end here -->

<style>
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.card-image {
    overflow: hidden;
}

.card-img-top {
    transition: transform 0.3s ease;
}

.product-card:hover .card-img-top {
    transform: scale(1.05);
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem;
    border-bottom-left-radius: 0.375rem;
}

.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
}

.input-group .btn-outline-secondary {
    border-color: #6c757d;
}
</style>
@endsection