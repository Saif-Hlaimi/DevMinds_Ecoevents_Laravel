@extends('layouts.admin')
@section('title', 'Edit Donation Cause')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Edit Donation Cause</h3>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
  <div class="card">
    <div class="card-body">
      <form action="{{ route('dashboard.admin.donation-causes.update', $donationCause->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $donationCause->title) }}" required>
          @error('title')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $donationCause->description) }}</textarea>
          @error('description')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Image</label>
          <div class="input-group">
            <input type="text" class="form-control" value="{{ $donationCause->image ? basename($donationCause->image) : 'No image selected' }}" readonly>
            <input type="file" name="image" id="image" accept="image/*" class="d-none">
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('image').click()">Browse</button>
          </div>
          @if($donationCause->image)
            <div class="mt-2">
              <img src="{{ asset('storage/' . $donationCause->image) }}" alt="Current Image" style="max-width: 200px;">
            </div>
          @endif
          @error('image')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="mb-3">
          <label for="goal_amount" class="form-label">Goal Amount ($)</label>
          <input type="number" name="goal_amount" id="goal_amount" class="form-control" step="0.01" min="0.01" value="{{ old('goal_amount', $donationCause->goal_amount) }}" required>
          @error('goal_amount')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="mb-3">
          <label for="sdg" class="form-label">Sustainable Development Goal (SDG)</label>
          <input type="text" name="sdg" id="sdg" class="form-control" value="{{ old('sdg', $donationCause->sdg) }}" required>
          @error('sdg')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Update Cause</button>
          <a href="{{ route('dashboard.admin.donation-causes.donation-causes') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const fileInput = document.getElementById('image');
  const fileText = document.querySelector('.input-group input[type="text"]');
  if (fileInput && fileText) {
    fileInput.addEventListener('change', function () {
      fileText.value = this.files.length > 0 ? this.files[0].name : 'No image selected';
    });
  }
});
</script>
@endpush
@endsection