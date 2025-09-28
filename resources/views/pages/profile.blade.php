@extends('layouts.app')

@section('title', 'Profile - EcoEvents')

@section('content')
<section class="page-banner bg-image pt-130 pb-130">
    <div class="container">
        <h2 class="wow fadeInUp">My Profile</h2>
        <div class="breadcrumb-list wow fadeInUp">
            <a href="{{ route('home') }}">Home :</a>
            <span class="primary-color">Profile</span>
        </div>
    </div>
</section>

<section class="pt-130 pb-130">
    <div class="container">
        <div class="row g-4">
            <!-- User Card -->
            <div class="col-lg-4">
                <div class="p-4 bg-image" style="border-radius: 12px; 
                    background: url('{{ $user->profile_image ? asset('storage/' . $user->profile_image) : asset('assets/images/bg/achievement-bg2.jpg') }}') center/cover;">
                    <div class="text-white">
                        <h4 class="mb-2">{{ $user->name }}</h4>
                        <p class="mb-1">{{ $user->email }}</p>
                        <p class="mb-1">{{ $user->phone ?? '-' }}</p>
                        <p class="mb-1">{{ $user->country ?? '-' }}</p>
                        @if($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="img-thumbnail mt-2" width="100">
                        @endif
                        <span class="badge bg-success text-uppercase mt-2">Role: {{ $user->role ?? 'user' }}</span>
                    </div>
                </div>
            </div>

            <!-- Details + Actions -->
            <div class="col-lg-8">
                <div class="p-4 border rounded-3">
                    <div class="section-header mb-4">
                        <h5><img src="{{ asset('assets/images/icon/leaf.svg') }}" alt=""> Account details</h5>
                        <h2>Welcome back, {{ \Illuminate\Support\Str::of($user->name)->before(' ') }}</h2>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <i class="fa-solid fa-user pe-2 primary-color"></i>
                            <span class="text-muted d-block">Full name</span>
                            <strong>{{ $user->name }}</strong>
                        </div>
                        <div class="col-md-6">
                            <i class="fa-solid fa-envelope pe-2 primary-color"></i>
                            <span class="text-muted d-block">Email</span>
                            <strong>{{ $user->email }}</strong>
                        </div>
                        <div class="col-md-6">
                            <i class="fa-solid fa-phone pe-2 primary-color"></i>
                            <span class="text-muted d-block">Phone</span>
                            <strong>{{ $user->phone ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <i class="fa-solid fa-globe pe-2 primary-color"></i>
                            <span class="text-muted d-block">Country</span>
                            <strong>{{ $user->country ?? '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <i class="fa-solid fa-id-badge pe-2 primary-color"></i>
                            <span class="text-muted d-block">Role</span>
                            <strong class="text-uppercase">{{ $user->role ?? 'user' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <i class="fa-solid fa-calendar pe-2 primary-color"></i>
                            <span class="text-muted d-block">Member since</span>
                            <strong>{{ optional($user->created_at)->format('M d, Y') }}</strong>
                        </div>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <a href="{{ route('home') }}" class="btn-one"><span>Go to home</span></a>
                        <button class="btn-one" data-bs-toggle="modal" data-bs-target="#editProfileModal"><span>Modify</span></button>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-two"><span>Logout</span></button>
                        </form>

                      <form action="{{ route('profile.destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete your profile?');">
    @csrf
    @method('DELETE')
    <button type="submit" style="background-color: red; width: 150px; height: 40px; border-radius: 5px; font-weight: bold;">
        <span style="color: white;">Delete Profile</span>
    </button>
</form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Profile Modal -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('profile.update') }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">Edit Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" value="{{ old('country', $user->country) }}" class="form-control" placeholder="Country">
                </div>

                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    @if($user->profile_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="img-thumbnail" width="100">
                        </div>
                    @endif
                    <input type="file" name="profile_image" class="form-control" accept="image/*">
                </div>

                @can('updateRole', $user)
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                @endcan
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
