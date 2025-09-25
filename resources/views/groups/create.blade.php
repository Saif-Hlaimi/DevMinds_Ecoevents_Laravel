@extends('layouts.app')
@section('title', isset($group) ? 'Edit Group' : 'Create Group')
@section('content')
<section class="page-banner bg-image pt-130 pb-130">
  <div class="container">
  <h2 class="wow fadeInUp" data-wow-duration="1.2s" data-wow-delay=".2s">{{ isset($group) ? 'Edit group' : 'Create a group' }}</h2>
    <div class="breadcrumb-list wow fadeInUp" data-wow-duration="1.4s" data-wow-delay=".4s">
      <a href="{{ route('home') }}">Home :</a>
      <a href="{{ route('groups.index') }}">Groups :</a>
  <span class="primary-color">{{ isset($group) ? 'Edit' : 'Create' }}</span>
    </div>
  </div>
</section>

<section class="pt-130 pb-130">
  <div class="container">
    <div class="p-4" style="border:1px solid #e5e7eb;border-radius:12px;">
      <form action="{{ isset($group) ? route('groups.update',$group->slug) : route('groups.store') }}" method="POST" enctype="multipart/form-data" novalidate>
        @csrf
        @if(isset($group)) @method('PUT') @endif

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="row g-4">
          <div class="col-lg-8">
            <div class="mb-3">
              <label class="mb-2">Group name</label>
              <input type="text" name="name" class="w-100 form-control @error('name') is-invalid @enderror" value="{{ old('name', $group->name ?? '') }}" placeholder="Ex: Eco Cleanup Rabat" required>
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="mb-2">Privacy</label>
              <select name="privacy" class="w-100 form-select @error('privacy') is-invalid @enderror" required>
                <option value="public" @selected(old('privacy',$group->privacy ?? 'public')==='public')>Public</option>
                <option value="private" @selected(old('privacy',$group->privacy ?? 'public')==='private')>Private</option>
              </select>
              @error('privacy')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="mb-2">Description</label>
              <textarea name="description" class="w-100 form-control @error('description') is-invalid @enderror" rows="5" placeholder="Describe your group's purpose, rules, and topics.">{{ old('description', $group->description ?? '') }}</textarea>
              @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="mb-2">Cover image URL (optional)</label>
              <input type="url" name="cover_image" class="w-100 form-control @error('cover_image') is-invalid @enderror" value="{{ old('cover_image', $group->cover_image ?? '') }}" placeholder="https://...">
              @error('cover_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="mb-2">Cover image file (optional)</label>
              <input type="file" name="cover_image_file" accept="image/*" class="w-100 form-control @error('cover_image_file') is-invalid @enderror">
              @error('cover_image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
              @isset($group)
                @php $cover = $group->cover_image_src; @endphp
                @if($cover)
                  <div class="mt-2" style="max-width:400px;border-radius:12px;overflow:hidden;">
                    <img src="{{ $cover }}" alt="cover" style="width:100%;height:200px;object-fit:cover;">
                  </div>
                @endif
              @endisset
            </div>
          </div>
          <div class="col-lg-4">
            <div class="p-3" style="background:#f9fafb;border:1px dashed #e5e7eb;border-radius:12px;">
              <div class="fw-bold mb-2">Tips</div>
              <ul class="mb-0 small">
                <li>Choose a clear, descriptive name</li>
                <li>Set privacy to match your audience</li>
                <li>Upload a cover image for better identity</li>
              </ul>
            </div>
            <button class="btn-one mt-3 w-100" type="submit">
              <span>{{ isset($group) ? 'Save changes' : 'Create group' }}</span> <i class="fa-solid fa-angles-right"></i>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
@endsection
