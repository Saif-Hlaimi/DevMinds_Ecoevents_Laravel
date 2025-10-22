@extends('layouts.admin')
@section('title', 'Create Donation Cause')
@section('content')
<div class="container-fluid">
  <h3 class="mb-3">Create Donation Cause</h3>
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  <div class="card">
    <div class="card-body">
      <form action="{{ route('dashboard.admin.donation-causes.store') }}" method="POST" enctype="multipart/form-data" id="causeForm">
        @csrf
        <div class="mb-3">
          <label for="title" class="form-label">Title</label>
          <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" placeholder="Enter title to generate AI description">
          <button type="button" class="btn btn-outline-primary mt-1" onclick="generateDescription()">Generate AI Description from Title</button>
          @error('title')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea name="description" id="description" class="form-control" rows="5" placeholder="Enter description to generate AI image">{{ old('description') }}</textarea>
          @error('description')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="mb-3">
          <label for="image" class="form-label">Image</label>
          <div class="input-group">
            <input type="text" class="form-control" value="No image selected" readonly id="image-preview-text">
            <input type="file" name="image" id="image" accept="image/*" class="d-none">
            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('image').click()">Browse</button>
            <button type="button" class="btn btn-primary ms-2" onclick="generateAIImage()">Generate AI Image</button>
          </div>
          <div class="mt-2">
            <img id="generated-image-preview" src="" alt="Generated Image Preview" class="img-thumbnail d-none" style="max-width: 200px; max-height: 200px;">
            <input type="hidden" name="generated_image_path" id="generated_image_path">
          </div>
          @error('image')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="mb-3">
          <label for="goal_amount" class="form-label">Goal Amount ($)</label>
          <input type="number" name="goal_amount" id="goal_amount" class="form-control" step="0.01" min="0.01" value="{{ old('goal_amount') }}">
          @error('goal_amount')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="mb-3">
          <label for="sdg" class="form-label">Sustainable Development Goal (SDG)</label>
          <input type="text" list="sdg-list" name="sdg" id="sdg" class="form-control" value="{{ old('sdg') }}" placeholder="Sustainable Development Goal (e.g., SDG1)">
          <datalist id="sdg-list">
            <option value="SDG1">SDG 1 - No Poverty</option>
            <option value="SDG2">SDG 2 - Zero Hunger</option>
            <option value="SDG3">SDG 3 - Good Health and Well-being</option>
            <option value="SDG4">SDG 4 - Quality Education</option>
            <option value="SDG5">SDG 5 - Gender Equality</option>
            <option value="SDG6">SDG 6 - Clean Water and Sanitation</option>
            <option value="SDG7">SDG 7 - Affordable and Clean Energy</option>
            <option value="SDG8">SDG 8 - Decent Work and Economic Growth</option>
            <option value="SDG9">SDG 9 - Industry, Innovation and Infrastructure</option>
            <option value="SDG10">SDG 10 - Reduced Inequalities</option>
            <option value="SDG11">SDG 11 - Sustainable Cities and Communities</option>
            <option value="SDG12">SDG 12 - Responsible Consumption and Production</option>
            <option value="SDG13">SDG 13 - Climate Action</option>
            <option value="SDG14">SDG 14 - Life Below Water</option>
            <option value="SDG15">SDG 15 - Life on Land</option>
            <option value="SDG16">SDG 16 - Peace, Justice and Strong Institutions</option>
            <option value="SDG17">SDG 17 - Partnerships for the Goals</option>
          </datalist>
          @error('sdg')
            <span class="text-danger small">{{ $message }}</span>
          @enderror
        </div>
        <div class="d-flex gap-2">
          <button type="submit" class="btn btn-primary">Create Cause</button>
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
  const fileText = document.getElementById('image-preview-text');
  if (fileInput && fileText) {
    fileInput.addEventListener('change', function () {
      fileText.value = this.files.length > 0 ? this.files[0].name : 'No image selected';
      document.getElementById('generated-image-preview').classList.add('d-none');
      document.getElementById('generated_image_path').value = '';
    });
  }
});

async function generateAIImage() {
  const description = document.getElementById('description').value.trim();
  if (!description) {
    alert('Please enter a description first.');
    return;
  }

  const button = event.target;
  const originalText = button.innerHTML;
  button.innerHTML = 'Generating...';
  button.disabled = true;

  const csrfToken = document.querySelector('meta[name="csrf-token"]');
  if (!csrfToken) {
    alert('CSRF token not found. Please refresh the page.');
    button.innerHTML = originalText;
    button.disabled = false;
    return;
  }
  const token = csrfToken.getAttribute('content');

  try {
    const response = await fetch('{{ route("dashboard.admin.donation-causes.generate-image") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
      },
      body: JSON.stringify({ description: description }),
    });

    const data = await response.json();

    if (data.success) {
      const previewImg = document.getElementById('generated-image-preview');
      const previewText = document.getElementById('image-preview-text');
      const hiddenPath = document.getElementById('generated_image_path');

      previewImg.src = data.image_url;
      previewImg.classList.remove('d-none');
      previewText.value = 'AI Generated Image';
      hiddenPath.value = data.file_path;

      // Clear manual file input
      document.getElementById('image').value = null;
    } else {
      alert('Error generating image: ' + data.error);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Failed to generate image. Please try again.');
  } finally {
    button.innerHTML = originalText;
    button.disabled = false;
  }
}

async function generateDescription() {
  const title = document.getElementById('title').value.trim();
  if (!title) {
    alert('Please enter a title first.');
    return;
  }

  const button = event.target;
  const originalText = button.innerHTML;
  button.innerHTML = 'Generating...';
  button.disabled = true;

  const csrfToken = document.querySelector('meta[name="csrf-token"]');
  if (!csrfToken) {
    alert('CSRF token not found. Please refresh the page.');
    button.innerHTML = originalText;
    button.disabled = false;
    return;
  }
  const token = csrfToken.getAttribute('content');

  try {
    const response = await fetch('{{ route("dashboard.admin.donation-causes.generate-description") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token,
      },
      body: JSON.stringify({ title: title }),
    });

    const data = await response.json();

    if (data.success) {
      document.getElementById('description').value = data.description;
      alert('Description generated successfully!');
    } else {
      alert('Error generating description: ' + data.error);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('Failed to generate description. Please try again.');
  } finally {
    button.innerHTML = originalText;
    button.disabled = false;
  }
}
</script>
@endpush
@endsection