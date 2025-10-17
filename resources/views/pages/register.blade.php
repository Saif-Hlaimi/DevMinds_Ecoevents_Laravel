@extends('layouts.app')
@section('title','Register')
@section('content')
<section class="auth-hero pt-130 pb-130">
    <style>
        .auth-card{box-shadow:0 10px 25px rgba(0,0,0,.08);border-radius:18px;background:#fff}
        .form-label{display:block;font-weight:600;margin-bottom:6px;color:#374151}
        .form-control{width:100%;border:1px solid #e5e7eb;border-radius:10px;padding:.7rem .9rem;font-size:14px}
        .form-control:focus{outline:none;border-color:#93c5fd;box-shadow:0 0 0 3px rgba(147,197,253,.35)}
        .input-with-icon{position:relative}
        .input-with-icon > i{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#6b7280}
        .input-with-icon > input.form-control{padding-left:40px}
        .invalid-feedback{color:#dc2626;font-size:12px;margin-top:4px}
        .text-muted{color:#6b7280}
        .auth-btn{border-radius:10px;padding:.75rem 1rem;background:#16a34a;color:#fff;border:none}
        .auth-btn:hover{background:#15803d}
        .oauth-btn{display:flex;align-items:center;justify-content:center;gap:8px;border:1px solid #e5e7eb;border-radius:10px;padding:.6rem 1rem;color:#111;background:#fff}
        .oauth-btn:hover{background:#f9fafb}
        .divider{display:flex;align-items:center;gap:12px;color:#6b7280}
        .divider::before,.divider::after{content:"";flex:1;height:1px;background:#e5e7eb}
        .password-toggle{position:absolute;right:10px;top:50%;transform:translateY(-50%);cursor:pointer;color:#6b7280}
        .strength{height:6px;border-radius:6px;background:#e5e7eb;overflow:hidden}
        .strength > span{display:block;height:100%;width:0;background:#ef4444;transition:width .25s ease, background .25s ease}
        .preview{display:none;max-width:100%;height:auto;border-radius:10px;border:1px solid #e5e7eb}
        /* Custom checkbox */
        .agree{display:flex;align-items:flex-start;gap:8px}
        .check{display:inline-flex;align-items:center;gap:10px;cursor:pointer;user-select:none}
        .check input{position:absolute;opacity:0;width:1px;height:1px}
        .check .box{width:20px;height:20px;border:2px solid #d1d5db;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;transition:all .2s ease;background:#fff}
        .check .tick{width:14px;height:14px;stroke:#fff;stroke-width:3px;stroke-linecap:round;stroke-linejoin:round;fill:none;opacity:0;transform:scale(.85);transition:opacity .2s ease, transform .2s ease}
        .check input:checked + .box{background:#16a34a;border-color:#16a34a}
        .check input:checked + .box .tick{opacity:1;transform:scale(1)}
        .check input:focus + .box{box-shadow:0 0 0 3px rgba(147,197,253,.35);border-color:#93c5fd}
        .check .label{color:#374151}
        .check .label a{color:#0ea5e9;text-decoration:underline}
    </style>
    <div class="container position-relative" style="z-index:2;">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="p-0 p-md-2">
                    <div class="row g-0 auth-card overflow-hidden">
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="h-100 w-100" style="background: url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=1200&auto=format&fit=crop') center/cover no-repeat; min-height:380px;"></div>
                        </div>
                        <div class="col-lg-6 bg-white" style="border-radius: 0 18px 18px 0;">
                            <div class="p-4 p-md-5">
                                <h2 class="mb-10">Create account</h2>
                                <p class="text-muted mb-30">Join EcoEvents and start making an impact.</p>
                                <div class="form-area">
                                    <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                                        @csrf
                                        @if ($errors->any())
                                            <div class="alert alert-danger py-2 px-3 mb-3">{{ $errors->first() }}</div>
                                        @endif
                                        <div class="mb-3">
                                            <label class="form-label" for="name">Full name</label>
                                            <div class="input-with-icon">
                                                <i class="fa-regular fa-user"></i>
                                                <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" required>
                                            </div>
                                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="email">Email address</label>
                                            <div class="input-with-icon">
                                                <i class="fa-regular fa-envelope"></i>
                                                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                                            </div>
                                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="phone">Phone</label>
                                            <div class="input-with-icon">
                                                <i class="fa-solid fa-phone"></i>
                                                <input id="phone" class="form-control" type="tel" name="phone" value="{{ old('phone') }}" placeholder="+216 12 345 678">
                                            </div>
                                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="country">Country</label>
                                            <div class="input-with-icon">
                                                <i class="fa-solid fa-globe"></i>
                                                <input id="country" class="form-control" list="country-list" type="text" name="country" value="{{ old('country') }}" placeholder="Select or type...">
                                                <datalist id="country-list">
                                                    <option value="Tunisia"></option>
                                                    <option value="France"></option>
                                                    <option value="Germany"></option>
                                                    <option value="United Kingdom"></option>
                                                    <option value="United States"></option>
                                                    <option value="Canada"></option>
                                                    <option value="Spain"></option>
                                                    <option value="Italy"></option>
                                                    <option value="Morocco"></option>
                                                    <option value="Algeria"></option>
                                                </datalist>
                                            </div>
                                            @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-2 position-relative">
                                            <label class="form-label" for="password">Password</label>
                                            <div class="input-with-icon">
                                                <i class="fa-solid fa-lock"></i>
                                                <input id="password" class="form-control" type="password" name="password" placeholder="••••••••" required>
                                                <span class="password-toggle" data-target="password"><i class="fa-regular fa-eye"></i></span>
                                            </div>
                                            <div class="strength mt-2"><span id="pwdStrengthBar"></span></div>
                                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-3 position-relative">
                                            <label class="form-label" for="password_confirmation">Confirm password</label>
                                            <div class="input-with-icon">
                                                <i class="fa-solid fa-lock"></i>
                                                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" placeholder="••••••••" required>
                                                <span class="password-toggle" data-target="password_confirmation"><i class="fa-regular fa-eye"></i></span>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="profile_image">Profile image (optional)</label>
                                            <div class="input-with-icon">
                                                <i class="fa-solid fa-image"></i>
                                                <input id="profile_image" class="form-control" type="file" name="profile_image" accept="image/*">
                                            </div>
                                            <img id="profilePreview" class="preview mt-2" alt="Profile preview">
                                            @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="mb-3 agree">
                                            <label class="check" for="terms">
                                              <input type="checkbox" id="terms" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                                              <span class="box" aria-hidden="true">
                                                <svg class="tick" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                                              </span>
                                              <span class="label">I agree to the <a href="{{ route('faq') }}" target="_blank" rel="noopener">Terms & Privacy</a></span>
                                            </label>
                                            @error('terms')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button class="auth-btn w-100 mt-2" type="submit">Create account</button>
                                    </form>
                                    <div class="text-center mt-3">
                                        <span class="text-muted">Already have an account?</span> 
                                        <a href="{{ route('login') }}" class="text-decoration-underline">Sign in</a>
                                    </div>
                                    <div class="pt-30 pb-20 d-flex justify-content-center"><div class="divider" style="width:100%"><span>OR</span></div></div>
                                </div>
                                <div class="login__with auth-oauth">
                                    <a href="{{ route('google.login') }}" class="oauth-btn">
                                        <img src="{{ asset('assets/images/icon/google.svg') }}" alt=""> Continue with Google
                                    </a>
                                    <a class="mt-15 oauth-btn" href="{{ route('facebook.login') }}">
                                        <img src="{{ asset('assets/images/icon/facebook.svg') }}" alt=""> Continue with Facebook
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</section>
@endsection

@push('scripts')
<script>
    // Password visibility toggle
    document.querySelectorAll('.password-toggle').forEach(function(t){
        t.addEventListener('click', function(){
            const id = this.getAttribute('data-target');
            const input = document.getElementById(id);
            if (!input) return;
            input.type = input.type === 'password' ? 'text' : 'password';
            this.innerHTML = input.type === 'password' ? '<i class="fa-regular fa-eye"></i>' : '<i class="fa-regular fa-eye-slash"></i>';
        });
    });

    // Simple password strength indicator
    const pwd = document.getElementById('password');
    const bar = document.getElementById('pwdStrengthBar');
    if (pwd && bar) {
        pwd.addEventListener('input', function(){
            const v = this.value;
            let score = 0;
            if (v.length >= 8) score += 25;
            if (/[A-Z]/.test(v)) score += 25;
            if (/[0-9]/.test(v)) score += 25;
            if (/[^A-Za-z0-9]/.test(v)) score += 25;
            bar.style.width = score + '%';
            bar.style.background = score < 50 ? '#ef4444' : (score < 75 ? '#f59e0b' : '#22c55e');
        });
    }

    // Profile image preview
    const fileInput = document.getElementById('profile_image');
    const preview = document.getElementById('profilePreview');
    if (fileInput && preview) {
        fileInput.addEventListener('change', function(){
            const f = this.files && this.files[0];
            if (!f) { preview.style.display='none'; return; }
            const url = URL.createObjectURL(f);
            preview.src = url;
            preview.style.display = 'block';
        });
    }
</script>
@endpush
