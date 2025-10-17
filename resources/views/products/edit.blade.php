@extends('layouts.app')
@section('title', 'Modifier le produit')
@section('content')
<!-- Page banner area start here -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">Modifier le produit</h2>
        <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
            <a href="{{ route('home') }}">Accueil :</a>
            <a href="{{ route('shop') }}">Boutique :</a>
            <a href="{{ route('products.index') }}">Produits :</a>
            <span class="primary-color">Modifier</span>
        </div>
    </div>
</section>
<!-- Page banner area end here -->

<!-- Product form area start here -->
<div class="shop pt-130 pb-130">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="product-form main-bg radius10 p-5">
                    <h3 class="mb-4">Modifier le produit</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Nom du produit *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price" class="form-label">Prix *</label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                           id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantity" class="form-label">Quantité *</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                           id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="image" class="form-label">Image du produit</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Formats acceptés : JPEG, PNG, JPG, GIF, WEBP (max : 2MB)</small>
                                    @if($product->image_path)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                                 alt="{{ $product->name }}"
                                                 style="max-width: 100px; max-height: 100px; border-radius: 5px;">
                                            <p class="small text-muted mt-1">Image actuelle</p>
                                        </div>
                                    @endif
                                    <div id="imagePreview" class="mt-2"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4"
                                              placeholder="Entrez la description du produit">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="caracteristiques" class="form-label">Caractéristiques</label>
                                    <textarea class="form-control @error('caracteristiques') is-invalid @enderror"
                                              id="caracteristiques" name="caracteristiques" rows="3"
                                              placeholder="Séparez les caractéristiques par des virgules">{{ old('caracteristiques', $product->caracteristiques) }}</textarea>
                                    @error('caracteristiques')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Exemple : Léger, Durable, Écologique, etc.</small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex gap-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-save me-2"></i>Mettre à jour
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                        <i class="fa-solid fa-arrow-left me-2"></i>Retour à la liste
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Product form area end here -->

<script>
document.getElementById('image')?.addEventListener('change', function(e) {
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
</script>
@endsection