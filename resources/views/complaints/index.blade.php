@extends('layouts.app')

@section('title', 'Complaints - EcoEvents')

@section('content')
<!-- === Bannière === -->
<section class="page-banner bg-image pt-130 pb-130">
  <div class="container">
    <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">
      {{ $complaint->exists ? 'Détails de la réclamation' : 'Nouvelle réclamation' }}
    </h2>
    <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
      <a href="{{ route('home') }}">Home :</a>
      <a href="{{ route('complaints.index') }}">Réclamations :</a>
      <span class="primary-color">{{ $complaint->exists ? 'Détail' : 'Création' }}</span>
    </div>
  </div>
</section>
<!-- === Bannière fin === -->


<section class="pt-130 pb-130">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h3 class="mb-0">Liste des réclamations</h3>
            <a href="{{ route('complaints.create') }}" class="btn-one">
                <span>Nouvelle réclamation</span> <i class="fa-solid fa-plus"></i>
            </a>
        </div>

        @if($complaints->count())
            <div class="row gy-4">
                @foreach($complaints as $complaint)
                    <div class="col-lg-4 col-md-6">
                        <div class="donation__item bor p-3">
                            <div class="image mb-30">
                                <img src="{{ asset('assets/images/event/default.jpg') }}" alt="{{ $complaint->subject }}">
                            </div>

                            <h3>
                                <a href="{{ route('complaints.show', $complaint) }}">
                                    {{ $complaint->subject }}
                                </a>
                            </h3>

                            <p>{{ Str::limit($complaint->message, 100) }}</p>
                            <p><strong>Catégorie :</strong> {{ $complaint->category ?? 'Générale' }}</p>
                            <p><strong>Priorité :</strong> 
                                <span class="badge 
                                    @if($complaint->priority == 'high') bg-danger 
                                    @elseif($complaint->priority == 'low') bg-success 
                                    @else bg-warning text-dark @endif">
                                    {{ ucfirst($complaint->priority ?? 'Moyenne') }}
                                </span>
                            </p>
                            <p><strong>Date :</strong> {{ $complaint->created_at->format('d M Y') }}</p>
                            <p><strong>Utilisateur :</strong> {{ $complaint->user->name ?? 'Anonyme' }}</p>

                            <div class="d-flex gap-2 mt-3">
                                <a href="{{ route('complaints.edit', $complaint) }}" class="btn-one">
                                    <span>Modifier</span> <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('complaints.destroy', $complaint) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-two" onclick="return confirm('Supprimer cette réclamation ?')">
                                        <span>Supprimer</span> <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <a class="donation__item-arrow" href="{{ route('complaints.show', $complaint) }}">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-5">
                {{ $complaints->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <p class="text-muted mb-0">Aucune réclamation trouvée.</p>
            </div>
        @endif
    </div>
</section>
@endsection