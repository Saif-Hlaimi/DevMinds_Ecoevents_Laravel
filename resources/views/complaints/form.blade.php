@extends('layouts.app')

@section('content')
<!-- Page banner area start here -->
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">
            {{ $complaint->exists ? 'Modifier la réclamation' : 'Nouvelle réclamation' }}
        </h2>
        <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
            <a href="{{ route('home') }}">Home :</a>
            <a href="{{ route('complaints.index') }}">Réclamations :</a>
            <span class="primary-color">
                {{ $complaint->exists ? 'Édition' : 'Création' }}
            </span>
        </div>
    </div>
</section>
<!-- Page banner area end here -->

<section class="pt-130 pb-130">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="p-4" style="border: 1px solid #e5e7eb; border-radius: 12px;">
                    <h3 class="mb-4">{{ $complaint->exists ? 'Modifier la réclamation' : 'Nouvelle réclamation' }}</h3>

                                        <form method="post" enctype="multipart/form-data"
                          action="{{ $complaint->exists ? route('complaints.update', $complaint) : route('complaints.store') }}">
                        @csrf
                        @if($complaint->exists)
                            @method('put')
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Sujet</label>
                            <input name="subject" class="form-control" value="{{ old('subject', $complaint->subject) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" class="form-control" rows="5" required>{{ old('message', $complaint->message) }}</textarea>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Catégorie</label>
                                <input name="category" class="form-control" value="{{ old('category', $complaint->category ?? 'general') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Priorité</label>
                                <select name="priority" class="form-select">
                                    @foreach(['low','medium','high'] as $p)
                                        <option @selected(old('priority', $complaint->priority ?? 'medium') === $p)>{{ $p }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pièce jointe (optionnel)</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type de réclamation</label>
                            @php $presetTypeId = old('complaint_type_id', $complaint->complaint_type_id ?? ($complaintType->id ?? null)); @endphp
                            @if($presetTypeId)
                                <input type="hidden" name="complaint_type_id" value="{{ $presetTypeId }}">
                                <input type="text" class="form-control" value="{{ optional($types->firstWhere('id', $presetTypeId))->name ?? 'Type' }}" readonly>
                            @else
                                <select name="complaint_type_id" class="form-select" required>
                                    <option value="" disabled selected>— Choisir —</option>
                                    @foreach($types as $t)
                                        <option value="{{ $t->id }}" @selected(old('complaint_type_id') == $t->id)>
                                            {{ ucfirst($t->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('complaint_type_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>
                        <div class="d-flex gap-2">
    <!-- Bouton Enregistrer / Mettre à jour en vert -->
    <button class="btn-one" style="background-color: #28a745; border-color: #28a745;" type="submit">
        <span>{{ $complaint->exists ? 'Mettre à jour' : 'Enregistrer' }}</span>
    </button>

    <!-- Bouton Annuler en gris clair -->
    <a href="{{ route('complaints.index') }}" class="btn-one" style="background-color: #6c757d; border-color: #6c757d;">
        <span>Annuler</span>
    </a>
</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection