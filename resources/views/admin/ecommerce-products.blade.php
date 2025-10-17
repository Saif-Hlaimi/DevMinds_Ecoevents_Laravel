// Fichier modifié : ecommerce-products.blade.php
@extends('layouts.admin')
@section('title', 'Ecommerce - Produits')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Gestion des produits</h3>
        <div>
            <a href="{{ route('dashboard.ecommerce.products.export-pdf') }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Exporter en PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <!-- Formulaire d'ajout/édition de produit -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header" id="formHeader">Ajouter un produit</div>
                <div class="card-body">
                    <form method="POST" id="productForm" action="{{ route('dashboard.ecommerce.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="id" id="productId">
                        <div class="mb-3">
                            <label class="form-label">Nom *</label>
                            <input name="name" id="nameInput" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="descriptionInput" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Caractéristiques</label>
                            <textarea name="caracteristiques" id="caracteristiquesInput" class="form-control @error('caracteristiques') is-invalid @enderror" rows="2" placeholder="Séparez par des virgules">{{ old('caracteristiques') }}</textarea>
                            @error('caracteristiques')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image du produit</label>
                            <input type="file" name="image" id="imageInput" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Formats acceptés : JPEG, PNG, JPG, GIF, WEBP (Max 2MB)</small>
                            <div id="imagePreview" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prix *</label>
                            <input name="price" id="priceInput" type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prix Promotionnel</label>
                            <input name="discount_price" id="discountPriceInput" type="number" step="0.01" min="0" class="form-control @error('discount_price') is-invalid @enderror" value="{{ old('discount_price') }}">
                            @error('discount_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantité *</label>
                            <input name="quantity" id="quantityInput" type="number" min="0" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity') }}" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="submitButton">
                                <i class="fas fa-plus me-2"></i><span id="submitText">Créer le produit</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">Liste des produits</div>
                <div class="card-body table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Caractéristiques</th>
                                <th>Prix</th>
                                <th>Prix Promo</th>
                                <th>Quantité</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr class="product-row" data-product-id="{{ $product->id }}"
                                    data-name="{{ htmlspecialchars($product->name) }}"
                                    data-description="{{ htmlspecialchars($product->description ?? '') }}"
                                    data-caracteristiques="{{ htmlspecialchars($product->caracteristiques ?? '') }}"
                                    data-price="{{ $product->price }}"
                                    data-discount-price="{{ $product->discount_price ?? '' }}"
                                    data-quantity="{{ $product->quantity }}"
                                    data-image-path="{{ $product->image_path ? asset('storage/' . $product->image_path) : '' }}">
                                    <td>
                                        <div class="position-relative">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
                                                     class="img-thumbnail product-image"
                                                     data-bs-toggle="modal" data-bs-target="#imageModal{{ $product->id }}"
                                                     style="cursor: pointer;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center product-image-placeholder">
                                                    <small class="text-muted">Aucune image</small>
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                <input type="file" name="image" class="form-control form-control-sm image-upload"
                                                       data-product-id="{{ $product->id }}" accept="image/*">
                                                <div class="image-preview-small mt-1" id="preview{{ $product->id }}"></div>
                                            </div>
                                        </div>

                                        @if($product->image_path)
                                            <div class="modal fade" id="imageModal{{ $product->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">{{ $product->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <img src="{{ asset('storage/' . $product->image_path) }}" class="img-fluid" alt="{{ $product->name }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ Str::limit($product->description ?? 'N/A', 50) }}</td>
                                    <td>{{ Str::limit($product->caracteristiques ?? 'N/A', 50) }}</td>
                                    <td>${{ number_format($product->price, 2) }}</td>
                                    <td>${{ number_format($product->discount_price ?? 0, 2) }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        @if($product->quantity > 0)
                                            <span class="badge bg-success">En stock</span>
                                        @else
                                            <span class="badge bg-danger">Rupture</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('dashboard.ecommerce.products.update', $product) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="name" value="{{ $product->name }}">
                                            <input type="hidden" name="description" value="{{ $product->description ?? '' }}">
                                            <input type="hidden" name="caracteristiques" value="{{ $product->caracteristiques ?? '' }}">
                                            <input type="hidden" name="price" value="{{ $product->price }}">
                                            <input type="hidden" name="discount_price" value="{{ $product->discount_price ?? '' }}">
                                            <input type="hidden" name="quantity" value="{{ $product->quantity }}">
                                            <button type="submit" class="btn btn-sm btn-success">Enregistrer</button>
                                        </form>
                                        <form class="d-inline" method="POST" action="{{ route('dashboard.ecommerce.products.destroy', $product) }}"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce produit ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">Aucun produit disponible.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Aperçu de l'image pour le formulaire d'ajout/édition
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagePreview');

    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('La taille du fichier doit être inférieure à 2 Mo.');
            this.value = '';
            preview.innerHTML = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="border rounded p-2">
                    <img src="${e.target.result}" class="img-thumbnail" style="max-height: 150px;">
                    <div class="mt-1">
                        <small class="text-muted">Aperçu : ${file.name}</small>
                    </div>
                </div>
            `;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Aperçu des images pour les produits existants dans le tableau
document.querySelectorAll('.image-upload').forEach(input => {
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const productId = this.getAttribute('data-product-id');
        const preview = document.getElementById('preview' + productId);

        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('La taille du fichier doit être inférieure à 2 Mo.');
                this.value = '';
                preview.innerHTML = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `
                    <small class="text-success">Nouvelle image sélectionnée : ${file.name}</small>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '';
        }
    });
});

// Remplir le formulaire lorsqu'on clique sur une ligne de produit
document.querySelectorAll('.product-row').forEach(row => {
    row.addEventListener('click', function(e) {
        // Éviter de déclencher l'événement si on clique sur un bouton ou un input
        if (e.target.tagName === 'BUTTON' || e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
            return;
        }

        const productId = this.getAttribute('data-product-id');
        const name = this.getAttribute('data-name');
        const description = this.getAttribute('data-description');
        const caracteristiques = this.getAttribute('data-caracteristiques');
        const price = this.getAttribute('data-price');
        const discountPrice = this.getAttribute('data-discount-price');
        const quantity = this.getAttribute('data-quantity');
        const imagePath = this.getAttribute('data-image-path');

        // Mettre à jour le formulaire
        const form = document.getElementById('productForm');
        const formHeader = document.getElementById('formHeader');
        const formMethod = document.getElementById('formMethod');
        const productIdInput = document.getElementById('productId');
        const nameInput = document.getElementById('nameInput');
        const descriptionInput = document.getElementById('descriptionInput');
        const caracteristiquesInput = document.getElementById('caracteristiquesInput');
        const priceInput = document.getElementById('priceInput');
        const discountPriceInput = document.getElementById('discountPriceInput');
        const quantityInput = document.getElementById('quantityInput');
        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const submitButton = document.getElementById('submitButton');
        const submitText = document.getElementById('submitText');

        form.action = `{{ url('dashboard/ecommerce/products') }}/${productId}`;
        formMethod.value = 'PUT';
        productIdInput.value = productId;
        nameInput.value = name;
        descriptionInput.value = description;
        caracteristiquesInput.value = caracteristiques;
        priceInput.value = price;
        discountPriceInput.value = discountPrice;
        quantityInput.value = quantity;
        formHeader.textContent = 'Éditer le produit';
        submitText.textContent = 'Mettre à jour le produit';
        submitButton.classList.remove('btn-primary');
        submitButton.classList.add('btn-success');

        // Afficher l'image existante dans l'aperçu
        if (imagePath) {
            imagePreview.innerHTML = `
                <div class="border rounded p-2">
                    <img src="${imagePath}" class="img-thumbnail" style="max-height: 150px;">
                    <div class="mt-1">
                        <small class="text-muted">Image actuelle</small>
                    </div>
                </div>
            `;
        } else {
            imagePreview.innerHTML = '';
        }
        imageInput.value = ''; // Réinitialiser le champ de fichier
    });
});

// Réinitialiser le formulaire pour ajouter un nouveau produit
document.getElementById('resetFormButton').addEventListener('click', function() {
    const form = document.getElementById('productForm');
    const formHeader = document.getElementById('formHeader');
    const formMethod = document.getElementById('formMethod');
    const productIdInput = document.getElementById('productId');
    const nameInput = document.getElementById('nameInput');
    const descriptionInput = document.getElementById('descriptionInput');
    const caracteristiquesInput = document.getElementById('caracteristiquesInput');
    const priceInput = document.getElementById('priceInput');
    const discountPriceInput = document.getElementById('discountPriceInput');
    const quantityInput = document.getElementById('quantityInput');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const submitButton = document.getElementById('submitButton');
    const submitText = document.getElementById('submitText');

    form.reset();
    form.action = '{{ route("dashboard.ecommerce.products.store") }}';
    formMethod.value = 'POST';
    productIdInput.value = '';
    formHeader.textContent = 'Ajouter un produit';
    submitText.textContent = 'Créer le produit';
    submitButton.classList.remove('btn-success');
    submitButton.classList.add('btn-primary');
    imagePreview.innerHTML = '';
});

// Ajout de contrôles de saisie (validation côté client)
document.getElementById('productForm').addEventListener('submit', function(e) {
    let isValid = true;
    const nameInput = document.getElementById('nameInput');
    const priceInput = document.getElementById('priceInput');
    const discountPriceInput = document.getElementById('discountPriceInput');
    const quantityInput = document.getElementById('quantityInput');
    const imageInput = document.getElementById('imageInput');
    const formMethod = document.getElementById('formMethod').value;

    // Réinitialiser les classes d'erreur
    [nameInput, priceInput, discountPriceInput, quantityInput, imageInput].forEach(input => {
        input.classList.remove('is-invalid');
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    });

    // Validation du nom
    if (!nameInput.value.trim()) {
        isValid = false;
        nameInput.classList.add('is-invalid');
        nameInput.nextElementSibling.textContent = 'Le nom du produit est requis.';
    }

    // Validation du prix
    if (isNaN(priceInput.value) || parseFloat(priceInput.value) < 0) {
        isValid = false;
        priceInput.classList.add('is-invalid');
        priceInput.nextElementSibling.textContent = 'Le prix doit être un nombre positif.';
    }

    // Validation du prix promotionnel
    if (discountPriceInput.value && (isNaN(discountPriceInput.value) || parseFloat(discountPriceInput.value) < 0)) {
        isValid = false;
        discountPriceInput.classList.add('is-invalid');
        discountPriceInput.nextElementSibling.textContent = 'Le prix promotionnel doit être un nombre positif.';
    } else if (discountPriceInput.value && parseFloat(discountPriceInput.value) > parseFloat(priceInput.value)) {
        isValid = false;
        discountPriceInput.classList.add('is-invalid');
        discountPriceInput.nextElementSibling.textContent = 'Le prix promotionnel doit être inférieur ou égal au prix normal.';
    }

    // Validation de la quantité
    if (isNaN(quantityInput.value) || parseInt(quantityInput.value) < 0 || !Number.isInteger(parseFloat(quantityInput.value))) {
        isValid = false;
        quantityInput.classList.add('is-invalid');
        quantityInput.nextElementSibling.textContent = 'La quantité doit être un entier positif.';
    }

    // Validation de l'image (seulement pour création)
    if (formMethod === 'POST' && !imageInput.files.length) {
        isValid = false;
        imageInput.classList.add('is-invalid');
        imageInput.nextElementSibling.textContent = 'Une image est requise pour un nouveau produit.';
    }

    if (!isValid) {
        e.preventDefault();
    }
});
</script>

<style>
.product-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
}

.product-image-placeholder {
    width: 80px;
    height: 80px;
    border: 2px dashed #dee2e6;
    border-radius: 0.375rem;
}

.img-thumbnail {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
}

.table td {
    vertical-align: middle;
}

.product-row {
    cursor: pointer;
}

.product-row:hover {
    background-color: #f8f9fa;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endsection