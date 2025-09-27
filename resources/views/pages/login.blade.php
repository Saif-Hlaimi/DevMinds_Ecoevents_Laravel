@extends('layouts.app')

@section('title','Login')

@section('content')
<section class="auth-hero pt-130 pb-130" style="position: relative; z-index: 1;">
	<div class="container position-relative" style="z-index: 2;">
		<div class="row justify-content-center">
			<div class="col-xl-10">
				<div class="p-0 p-md-2">
					<div class="row g-0 auth-card overflow-hidden" style="position: relative; z-index: 2;">
						
						<!-- Colonne image -->
						<div class="col-lg-6 d-none d-lg-block">
							<div class="h-100 w-100" 
							     style="background: url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=1200&auto=format&fit=crop') 
							            center/cover no-repeat; 
							            min-height:380px;">
							</div>
						</div>
						
						<!-- Colonne formulaire -->
						<div class="col-lg-6 bg-white" 
						     style="border-radius: 0 18px 18px 0; position: relative; z-index: 10;">
							
							<div class="p-4 p-md-5">
								<h2 class="mb-10">Welcome Back</h2>
								<p class="text-muted mb-30">Sign in with your EcoEvents account.</p>
								
								<div class="form-area">
									<form action="{{ route('login.attempt') }}" method="POST" novalidate>
										@csrf

										{{-- Erreurs globales --}}
										@if ($errors->any())
											<div class="alert alert-danger py-2 px-3 mb-3">{{ $errors->first() }}</div>
										@endif

										{{-- Email --}}
										<div class="position-relative mb-3">
											<i class="fa-regular fa-envelope position-absolute" 
											   style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
											<input type="email" name="email" 
											       value="{{ old('email') }}" 
											       placeholder="Email address" 
											       required 
											       class="form-control ps-5">
										</div>

										{{-- Password --}}
										<div class="position-relative mb-3">
											<i class="fa-solid fa-lock position-absolute" 
											   style="left:14px;top:50%;transform:translateY(-50%);color:#6b7280"></i>
											<input type="password" name="password" 
											       placeholder="Password" 
											       required 
											       class="form-control ps-5">
										</div>

										{{-- Remember me + Forgot password --}}
										<div class="d-flex align-items-center justify-content-between mb-2">
											<label class="d-flex align-items-center gap-2 mb-0">
												<input type="checkbox" name="remember" value="1"> <span>Remember me</span>
											</label>
											@if (Route::has('password.request'))
												<a class="text-decoration-underline" href="{{ route('password.request') }}">
													Forgot password?
												</a>
											@endif
										</div>

										{{-- Bouton --}}
										<button class="auth-btn w-100 mt-2 btn btn-primary" type="submit">Sign in</button>
									</form>

									{{-- Lien Register --}}
									<div class="text-center mt-3">
										<span class="text-muted">No account?</span> 
										<a href="{{ route('register') }}" class="text-decoration-underline">Create one</a>
									</div>

									<span class="or pt-30 pb-40 d-block text-center">OR</span>
								</div>

								{{-- OAuth --}}
								<div class="login__with auth-oauth">
										<a href="{{ route('google.login') }}" class="d-flex align-items-center justify-content-center gap-2 py-2 mb-2 btn btn-outline-danger">
											<img src="{{ asset('assets/images/icon/google.svg') }}" alt="" width="20"> Continue with Google
										</a>
										<a href="{{ route('facebook.login') }}" class="d-flex align-items-center justify-content-center gap-2 py-2 btn btn-primary">
											<i class="fa-brands fa-facebook-f"></i> Continue with Facebook
										</a>
									</div>
							</div>
						</div> {{-- fin col formulaire --}}
						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
@endsectionF