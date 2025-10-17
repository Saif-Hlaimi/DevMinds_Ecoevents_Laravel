@extends('layouts.app')
@section('title','Shop')
@section('content')
	<!-- Page banner area start here -->
	<section class="page-banner bg-image pt-130 pb-130">
		<div class="container">
			<h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Shop Page</h2>
			<div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
				<a href="{{ route('home') }}">Home :</a>
				<span class="primary-color">Shop</span>
			</div>
		</div>
	</section>
	<!-- Page banner area end here -->

	<!-- Shop page area start here -->
	<div class="shop pt-130 pb-130">
		<div class="container">
			{{-- Message de succès pour la commande --}}
			@if(session('success'))
				<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
					<i class="fa-solid fa-check-circle me-2"></i>
					{{ session('success') }}
					<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
				</div>
			@endif

			<div class="row mb-4">
				<div class="col-12">
					<div class="d-flex justify-content-between align-items-center">
						<h3>Nos produits</h3>
					</div>
				</div>
			</div>

			<!-- Barre de recherche et de tri -->
			<div class="row mb-4">
				<div class="col-12">
					<div class="top-bar sub-bg mb-4 d-flex flex-wrap justify-content-between align-items-center main-bg radius10 px-4 py-3">
						<span>Showing {{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} results</span>
						<div class="d-flex align-items-center gap-3">
							<!-- Barre de recherche -->
							<form method="GET" action="{{ route('shop') }}" class="d-flex align-items-center gap-2">
								<div class="input-group input-group-sm" style="width: 250px;">
									<input type="text" 
										   name="search" 
										   class="form-control" 
										   placeholder="Rechercher un produit..." 
										   value="{{ request('search') }}"
										   aria-label="Rechercher">
									@if(request('search'))
										<a href="{{ route('shop') }}" class="btn btn-outline-secondary" type="button">
											<i class="fa-solid fa-times"></i>
										</a>
									@endif
									<button class="btn btn-primary" type="submit">
										<i class="fa-solid fa-search"></i>
									</button>
								</div>
							</form>

							<!-- Sélecteur de tri -->
							<form method="GET" action="{{ route('shop') }}">
								@if(request('search'))
									<input type="hidden" name="search" value="{{ request('search') }}"/>
								@endif
								<select name="sort" id="shop-sort" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
									<option value="newest" {{ request('sort','newest')==='newest' ? 'selected' : '' }}>Trier par plus récent</option>
									<option value="oldest" {{ request('sort')==='oldest' ? 'selected' : '' }}>Trier par plus ancien</option>
									<option value="price_asc" {{ request('sort')==='price_asc' ? 'selected' : '' }}>Prix: du plus bas au plus élevé</option>
									<option value="price_desc" {{ request('sort')==='price_desc' ? 'selected' : '' }}>Prix: du plus élevé au plus bas</option>
									<option value="quantity_asc" {{ request('sort')==='quantity_asc' ? 'selected' : '' }}>Quantité: faible → élevée</option>
									<option value="quantity_desc" {{ request('sort')==='quantity_desc' ? 'selected' : '' }}>Quantité: élevée → faible</option>
								</select>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Résultats de recherche -->
			@if(request('search'))
				<div class="row mb-3">
					<div class="col-12">
						<div class="alert alert-info d-flex justify-content-between align-items-center">
							<div>
								<i class="fa-solid fa-info-circle me-2"></i>
								Résultats de recherche pour : <strong>"{{ request('search') }}"</strong>
								@if($products->total() > 0)
									<span class="ms-2">({{ $products->total() }} produit(s) trouvé(s))</span>
								@endif
							</div>
							<a href="{{ route('shop') }}" class="btn btn-sm btn-outline-primary">
								<i class="fa-solid fa-times me-1"></i>
								Effacer la recherche
							</a>
						</div>
					</div>
				</div>
			@endif

			<!-- Produits -->
			<div class="row g-4">
				<div class="col-12">
					<div class="product light">
						<div class="container">
							<div class="row g-4">
								@forelse ($products as $product)
									<div class="col-md-4">
										<div class="item">
											@if($product->image_path)
												<img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}">
											@else
												<img src="{{ asset('assets/images/product/product1.png') }}" alt="{{ $product->name }}">
											@endif
											<div class="content">
												<h4><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h4>
												@if($product->discount_price)
													<span class="text-primary fw-bold">{{ number_format($product->discount_price, 2) }} €</span>
													<span class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 2) }} €</span>
												@else
													<span class="text-primary fw-bold">{{ number_format($product->price, 2) }} €</span>
												@endif
												@if($product->quantity > 0)
													<small class="text-success d-block">En stock ({{ $product->quantity }})</small>
												@else
													<small class="text-danger d-block">Rupture</small>
												@endif
											</div>
											<div class="product-actions">
												<div class="d-flex flex-column gap-2">
													<!-- Bouton pour voir le produit spécifique -->
													<a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm">
														<i class="fa-solid fa-eye me-1"></i>
														Voir le produit
													</a>
													
													@if($product->quantity > 0)
														<form class="add-to-cart-form" data-product-id="{{ $product->id }}">
															<div class="quantity-input mb-2">
																<label for="quantity_{{ $product->id }}" class="form-label small">Quantité:</label>
																<input type="number" 
																	   id="quantity_{{ $product->id }}" 
																	   name="quantity" 
																	   value="1" 
																	   min="1" 
																	   max="{{ $product->quantity }}"
																	   class="form-control form-control-sm"
																	   style="width: 80px; display: inline-block;">
															</div>
															<button type="submit" class="btn btn-primary btn-sm add-to-cart-btn w-100">
																<i class="fa-solid fa-cart-plus me-1"></i>
																Ajouter au panier
															</button>
														</form>
													@else
														<button class="btn btn-secondary btn-sm w-100" disabled>
															<i class="fa-solid fa-times me-1"></i>
															Rupture de stock
														</button>
													@endif
												</div>
											</div>
											<div class="icon">
												<a href="#0"><i class="fa-solid fa-heart"></i></a>
												<a href="{{ route('cart.index') }}" class="cart-link">
													<i class="fa-solid fa-cart-shopping"></i>
													<span class="cart-count badge bg-primary" style="display: none;">0</span>
												</a>
											</div>
										</div>
									</div>
								@empty
									<div class="col-12 text-center py-5">
										@if(request('search'))
											<h5 class="text-muted">Aucun produit trouvé pour "{{ request('search') }}"</h5>
											<p class="text-muted">Essayez avec d'autres termes de recherche.</p>
											<a href="{{ route('shop') }}" class="btn btn-primary mt-3">
												<i class="fa-solid fa-arrow-left me-1"></i>
												Voir tous les produits
											</a>
										@else
											<h5 class="text-muted">Aucun produit disponible</h5>
											<p class="text-muted">Revenez bientôt pour découvrir nos nouveaux produits.</p>
										@endif
									</div>
								@endforelse
							</div>
							
							<!-- Pagination -->
							@if($products->hasPages())
								<div class="pt-30 bor-top mt-65">
									@if($products->onFirstPage())
										<span class="blog-pegi disabled">Précédent</span>
									@else
										<a class="blog-pegi" href="{{ $products->previousPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('sort') ? '&sort=' . request('sort') : '' }}">Précédent</a>
									@endif

									@foreach(range(1, $products->lastPage()) as $page)
										<a class="blog-pegi {{ $page == $products->currentPage() ? 'active' : '' }}" 
								   href="{{ $products->url($page) }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('sort') ? '&sort=' . request('sort') : '' }}">{{ $page }}</a>
									@endforeach

									@if($products->hasMorePages())
										<a class="blog-pegi" href="{{ $products->nextPageUrl() }}{{ request('search') ? '&search=' . request('search') : '' }}{{ request('sort') ? '&sort=' . request('sort') : '' }}">
											<i class="fa-solid blog_pegi_arrow fa-arrow-right-long"></i>
										</a>
									@else
										<span class="blog-pegi disabled">
											<i class="fa-solid blog_pegi_arrow fa-arrow-right-long"></i>
										</span>
									@endif
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Shop page area end here -->

	<!-- Our info area start here -->
	<div class="our-info" data-background="{{ asset('assets/images/bg/our-info.jpg') }}">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-3 wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
					<a href="{{ route('home') }}" class="our-info__logo mb-4 mb-lg-0">
						<img src="{{ asset('assets/images/logo/logo-light.svg') }}" alt="logo">
					</a>
				</div>
				<div class="col-lg-5 wow fadeInDown" data-wow-duration="1.6s" data-wow-delay=".6s">
					<div class="our-info__input">
						<input type="text" placeholder="Your email Address">
						<i class="fa-regular fa-envelope our-info__input-envelope"></i>
						<i class="fa-solid fa-paper-plane our-info__input-plane"></i>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="our-info__social-icon float-lg-end float-none mt-4 mt-lg-0">
						<a class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s" href="#0"><i class="fa-brands fa-facebook-f"></i></a>
						<a class="wow fadeInUp" data-wow-duration="1.3s" data-wow-delay=".3s" href="#0"><i class="fa-brands fa-twitter"></i></a>
						<a class="wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s" href="#0"><i class="fa-brands fa-linkedin-in"></i></a>
						<a class="wow fadeInUp" data-wow-duration="1.5s" data-wow-delay=".5s" href="#0"><i class="fa-brands fa-youtube"></i></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Our info area end here -->
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du formulaire d'ajout au panier
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const productId = this.dataset.productId;
            const quantityInput = this.querySelector('input[name="quantity"]');
            const quantity = parseInt(quantityInput.value);
            const submitBtn = this.querySelector('.add-to-cart-btn');
            
            // Désactiver le bouton pendant la requête
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i>Ajout en cours...';
            
            // Envoyer la requête AJAX
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errData => {
                        throw new Error(errData.message || 'Erreur serveur');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Afficher un message de succès
                    showNotification('success', data.message);
                    
                    // Mettre à jour le compteur du panier
                    updateCartCount(data.totalItems);
                    
                    // Émettre un événement global pour mettre à jour le panier
                    document.dispatchEvent(new CustomEvent('cartUpdated', {
                        detail: { totalItems: data.totalItems }
                    }));
                    
                    // Réinitialiser le formulaire
                    quantityInput.value = 1;
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('error', error.message || 'Une erreur est survenue lors de l\'ajout au panier');
            })
            .finally(() => {
                // Réactiver le bouton
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fa-solid fa-cart-plus me-1"></i>Ajouter au panier';
            });
        });
    });
    
    // Fonction pour afficher les notifications
    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Supprimer automatiquement après 3 secondes
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }
    
    // Fonction pour mettre à jour le compteur du panier
    function updateCartCount(count) {
        const cartCounts = document.querySelectorAll('.cart-count');
        cartCounts.forEach(counter => {
            if (count > 0) {
                counter.textContent = count;
                counter.style.display = 'inline-block';
            } else {
                counter.style.display = 'none';
            }
        });
    }
    
    // Charger le contenu du panier au chargement de la page
    loadCartContent();
    
    function loadCartContent() {
        fetch('{{ route("cart.content") }}', {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors du chargement du panier');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateCartCount(data.totalItems);
            }
        })
        .catch(error => {
            console.error('Erreur lors du chargement du panier:', error);
        });
    }

    // Auto-dismiss des alertes de succès après 5 secondes
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            const closeButton = successAlert.querySelector('.btn-close');
            if (closeButton) {
                closeButton.click();
            }
        }, 5000);
    }
});
</script>
@endpush