@extends('layouts.app')
@section('title', 'Create Donation Cause')
@section('content')
    <!-- Auth hero start (modernized) -->
    <section class="auth-hero pt-130 pb-130">
        <div class="container position-relative" style="z-index:2;">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="p-0 p-md-2">
                        <div class="row g-0 auth-card overflow-hidden">
                            <div class="col-lg-6 d-none d-lg-block">
                                <div class="h-100 w-100" style="background: url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat; min-height:380px;"></div>
                            </div>
                            <div class="col-lg-6 bg-white" style="border-radius: 0 18px 18px 0; z-index: 1000;">
                                <div class="p-4 p-md-5">
                                    <h2 class="mb-10">Create a New Donation Cause</h2>
                                    <p class="text-muted mb-30">Fill in the details to create a new donation cause for EcoEvents.</p>
                                    <div class="form-area">
                                        <form action="{{ route('donation-causes.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @if (session('status'))
                                                <div class="alert alert-success py-2 px-3 mb-3">{{ session('status') }}</div>
                                            @endif
                                            <div class="mb-3">
                                                <div class="position-relative">
                                                    <i class="fa-solid fa-hand-holding-heart position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280;z-index:1;"></i>
                                                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Cause Title" class="form-control" style="padding-left:42px;">
                                                </div>
                                                @error('title')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div class="position-relative">
                                                    <i class="fa-solid fa-info-circle position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280;z-index:1;"></i>
                                                    <textarea name="description" placeholder="Cause Description" class="form-control" style="padding-left:42px; height:150px; background-color: #ffffff !important;">{{ old('description') }}</textarea>
                                                </div>
                                                @error('description')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div class="position-relative">
                                                    <i class="fa-solid fa-image position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280;z-index:1;"></i>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" placeholder="Choose Image" style="padding-left:42px;" readonly>
                                                        <input type="file" name="image" accept="image/*" class="d-none" id="image-input">
                                                        <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('image-input').click()">Browse</button>
                                                    </div>
                                                </div>
                                                @error('image')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div class="position-relative">
                                                    <i class="fa-solid fa-dollar-sign position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280;z-index:1;"></i>
                                                    <input type="number" name="goal_amount" step="0.01" min="0.01" value="{{ old('goal_amount') }}" placeholder="Goal Amount" class="form-control" style="padding-left:42px;">
                                                </div>
                                                @error('goal_amount')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <div class="position-relative">
                                                    <i class="fa-solid fa-globe position-absolute" style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280;z-index:1;"></i>
                                                    <input type="text" list="sdg-list" name="sdg" value="{{ old('sdg') }}" placeholder="Sustainable Development Goal (e.g., SDG1)" class="form-control" style="padding-left:42px;">
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
                                                </div>
                                                @error('sdg')
                                                    <span class="text-danger mt-1 d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <button class="auth-btn w-100 mt-2" type="submit">Create Cause</button>
                                        </form>
                                        <div class="text-center mt-3">
                                            <a href="{{ route('donation-causes.index') }}" class="text-decoration-underline">Back to Donation Causes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Auth hero end -->

    <!-- Our info area start here -->
    <div class="our-info" data-background="https://images.unsplash.com/photo-1482192505345-5655af888cc4?q=80&w=1600&auto=format&fit=crop">
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
document.addEventListener('DOMContentLoaded', function () {
  const fileInput = document.getElementById('image-input');
  const fileText = document.querySelector('.input-group input[type="text"]');
  if (fileInput && fileText) {
    fileInput.addEventListener('change', function () {
      fileText.value = this.files.length > 0 ? this.files[0].name : 'No image selected';
    });
  }
});
</script>
@endpush