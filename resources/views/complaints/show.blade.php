@extends('layouts.app')

@section('title', $complaint->exists ? 'Modifier la réclamation' : 'Nouvelle réclamation')

@section('content')
<!-- === Bannière === -->
<section class="page-banner bg-image pt-130 pb-130">
  <div class="container">
    <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">
      {{ $complaint->exists ? 'Détails de la réclamation' : 'Nouvelle réclamation' }}
    </h2>
    <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
      <a href="{{ route('home') }}" class="text-white-50">Home</a> /
      <a href="{{ route('complaints.index') }}" class="text-white-50">Réclamations</a> /
      <span class="fw-bold">{{ $complaint->exists ? 'Détail' : 'Création' }}</span>
    </div>
  </div>
</section>

<!-- === Contenu principal === -->
<section class="py-5 bg-light">
  <div class="container">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
      <div class="card-body p-5">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <h3 class="fw-bold mb-1">{{ $complaint->subject }}</h3>
            <p class="text-muted mb-0">
              <i class="fa-regular fa-user me-1"></i>{{ $complaint->user->name }}
              • <i class="fa-regular fa-clock me-1"></i>{{ $complaint->created_at->diffForHumans() }}
            </p>
          </div>

          <div class="d-flex gap-2">
            @can('update', $complaint)
              <a href="{{ route('complaints.edit', $complaint) }}" class="btn btn-outline-success btn-sm">
                <i class="fa-solid fa-pen"></i>Edit
              </a>
            @endcan
            @can('delete', $complaint)
              <form method="post" action="{{ route('complaints.destroy', $complaint) }}" onsubmit="return confirm('Supprimer cette réclamation ?')">
                @csrf @method('delete')
                <button class="btn btn-outline-danger btn-sm">
                  <i class="fa-solid fa-trash"></i> Delete
                </button>
              </form>
            @endcan
          </div>
        </div>

        <!-- Infos principales -->
        <div class="bg-white rounded-3 shadow-sm p-4 mb-4">
          <ul class="list-inline mb-0">
            <li class="list-inline-item me-3">
              <span class="badge bg-secondary px-3 py-2 text-uppercase">
                <i class="fa-solid fa-flag"></i> {{ $complaint->status }}
              </span>
            </li>
            <li class="list-inline-item me-3">
              @php $colors=['low'=>'bg-success','medium'=>'bg-warning','high'=>'bg-danger']; @endphp
              <span class="badge {{ $colors[$complaint->priority] ?? 'bg-secondary' }} px-3 py-2 text-uppercase">
                <i class="fa-solid fa-signal"></i> {{ $complaint->priority }}
              </span>
            </li>
            <li class="list-inline-item me-3">
              <i class="fa-solid fa-layer-group text-success"></i>
              <strong>Catégorie :</strong> {{ ucfirst($complaint->category) }}
            </li>
            
            
          </ul>
        </div>

        <!-- Message -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="fw-bold mb-3 text-success">
              <i class="fa-solid fa-comment-dots me-2"></i>Message
            </h5>
            <p class="mb-0" style="white-space: pre-wrap;">{{ $complaint->message }}</p>
          </div>
        </div>

        <!-- Pièce jointe -->
        @if($complaint->attachment_path)
          <div class="mb-4">
            <a class="btn btn-outline-secondary" target="_blank" href="{{ asset('storage/'.$complaint->attachment_path) }}">
              <i class="fa-solid fa-paperclip"></i> See more
            </a>
          </div>
        @endif

        <!-- Section admin -->
        @can('update',$complaint)
        @if(auth()->user()->role === 'admin')
        <hr class="my-4">
        <h5 class="fw-bold mb-3 text-success">
          <i class="fa-solid fa-gears me-2"></i>Gestion administrative
        </h5>

        <form method="post" action="{{ route('complaints.update',$complaint) }}">
          @csrf @method('put')
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Statut</label>
              <select name="status" class="form-select shadow-sm">
                @foreach(['open','pending','resolved','closed'] as $s)
                  <option @selected($complaint->status===$s)>{{ $s }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Priorité</label>
              <select name="priority" class="form-select shadow-sm">
                @foreach(['low','medium','high'] as $p)
                  <option @selected($complaint->priority===$p)>{{ $p }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Assigner à (ID utilisateur)</label>
              <input name="assigned_to" class="form-control shadow-sm" value="{{ $complaint->assigned_to }}">
            </div>
          </div>

          <button class="btn btn-success mt-3 px-4">
            <i class="fa-solid fa-floppy-disk me-1"></i> Mettre à jour
          </button>
        </form>
        @endif
        @endcan

      </div>
    </div>
  </div>
</section>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

 
</style>
@endsection
