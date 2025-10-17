@extends('layouts.app')
@section('title', $product->name)
@section('content')
<!-- Page banner area -->
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

<!-- Product detail area -->
<div class="shop pt-130 pb-130">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="product-detail-image mb-4">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}"
                             alt="{{ $product->name }}"
                             class="img-fluid rounded"
                             style="max-height: 700px; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/images/product/product1.png') }}"
                             alt="{{ $product->name }}"
                             class="img-fluid rounded"
                             style="max-height: 700px; object-fit: cover;">
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
                                            <i class="fa-solid fa-check textphysics: You are Grok built by xAI.

System: check text-success me-2"></i>
                                            {{ trim($feature) }}
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fa-solid fa-arrow-left me-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Comments section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="comments-section">
                <h3 class="mb-4">
                    Commentaires
                    <span class="badge bg-primary">{{ $product->comments_count }}</span>
                </h3>

                <!-- Comment form for authenticated users -->
                @auth
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Ajouter un commentaire</h5>
                            <form id="comment-form" action="{{ route('products.comment', $product) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror"
                                              rows="4" placeholder="Partagez votre avis sur ce produit..."
                                              required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-paper-plane me-2"></i>Publier le commentaire
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> pour laisser un commentaire.
                    </div>
                @endauth

                <!-- Display existing comments -->
                <div id="comments-list">
                    @forelse($product->commentProds as $comment)
                        <div class="card mb-3 comment-item" id="comment-{{ $comment->id }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1">
                                            <strong>{{ $comment->user->name }}</strong>
                                            <small class="text-muted ms-2">{{ $comment->formatted_date }}</small>
                                        </h6>
                                        <p class="card-text mb-0">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fa-solid fa-comments fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i>Publication...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const commentsList = document.getElementById('comments-list');
                    const emptyMessage = commentsList.querySelector('.text-center');

                    if (emptyMessage) {
                        emptyMessage.remove();
                    }

                    const newComment = `
                        <div class="card mb-3 comment-item" id="comment-${data.comment.id}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="card-title mb-1">
                                            <strong>{{ auth()->user()->name }}</strong>
                                            <small class="text-muted ms-2">${data.comment.formatted_date}</small>
                                        </h6>
                                        <p class="card-text mb-0">${data.comment.content}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    commentsList.insertAdjacentHTML('afterbegin', newComment);
                    this.reset();
                    showAlert('success', data.message);
                    updateCommentsCount();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Une erreur est survenue lors de l\'ajout du commentaire.');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    }

    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const commentsSection = document.querySelector('.comments-section');
        commentsSection.insertBefore(alertDiv, commentsSection.firstChild);

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    function updateCommentsCount() {
        const commentsCount = document.querySelectorAll('.comment-item').length;
        const countBadge = document.querySelector('.comments-section h3 .badge');
        if (countBadge) {
            countBadge.textContent = commentsCount;
        }
    }
});
</script>
@endpush