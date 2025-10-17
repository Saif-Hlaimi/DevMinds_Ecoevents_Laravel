<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

use Laravel\Socialite\Facades\Socialite;
use Exception;

use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLogin()
    {
        return view('pages.login');
    }

    /**
     * Traiter la tentative de connexion
     */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $remember = (bool)($data['remember'] ?? false);

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Welcome back!');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegister()
    {
        return view('pages.register');
    }

    /**
     * Traiter l'inscription d'un nouvel utilisateur
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'country'  => ['nullable', 'string', 'max:100'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // Gestion image de profil
        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('profiles', 'public');
        }

        $user = User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password']),
            'phone'         => $data['phone'] ?? null,
            'country'       => $data['country'] ?? null,
            'profile_image' => $imagePath,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'))->with('success', 'Account created successfully!');
    }

    /**
     * Déconnecter l'utilisateur
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Afficher le formulaire de mot de passe oublié
     */
    public function showForgotPassword()
    {
        return view('pages.forgot-password');
    }

    /**
     * Envoyer le lien de réinitialisation du mot de passe
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Afficher le formulaire de réinitialisation du mot de passe
     */
    public function showResetPassword(Request $request, $token)
    {
        return view('pages.reset-password', [
            'token' => $token, 
            'email' => $request->email
        ]);
    }

    /**
     * Traiter la réinitialisation du mot de passe
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Redirection vers Google OAuth
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            Log::error('Google redirect error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Google authentication is currently unavailable. Please try another method.'
            ]);
        }
    }

    /**
     * Callback de Google OAuth
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser->getEmail()) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Google account email is required. Please ensure your Google account has an email address.'
                ]);
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? 'Google User',
                    'email' => $googleUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
                
                Log::info('New user registered via Google: ' . $googleUser->getEmail());
            } else {
                if (!$user->avatar && $googleUser->getAvatar()) {
                    $user->update(['avatar' => $googleUser->getAvatar()]);
                }
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            Log::info('User logged in via Google: ' . $user->email);

            return redirect()->intended(route('home'))->with('success', 'Welcome back!');

        } catch (Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Google login failed. Please try again or use another login method.'
            ]);
        }
    }

    /**
     * Redirection vers Facebook OAuth
     */
    public function redirectToFacebook()
    {
        try {
            return Socialite::driver('facebook')->redirect();
        } catch (Exception $e) {
            Log::error('Facebook redirect error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Facebook authentication is currently unavailable. Please try another method.'
            ]);
        }
    }

    /**
     * Callback de Facebook OAuth
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();

            if (!$facebookUser->getEmail()) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Facebook account email is required. Please ensure your Facebook account has an email address or try another login method.'
                ]);
            }

            $user = User::where('email', $facebookUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name' => $facebookUser->getName() ?? $facebookUser->getNickname() ?? 'Facebook User',
                    'email' => $facebookUser->getEmail(),
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'avatar' => $facebookUser->getAvatar(),
                ]);
                
                Log::info('New user registered via Facebook: ' . $facebookUser->getEmail());
            } else {
                if (!$user->avatar && $facebookUser->getAvatar()) {
                    $user->update(['avatar' => $facebookUser->getAvatar()]);
                }
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            Log::info('User logged in via Facebook: ' . $user->email);

            return redirect()->intended(route('home'))->with('success', 'Welcome back!');

        } catch (Exception $e) {
            Log::error('Facebook OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Facebook login failed. Please try again or use another login method.'
            ]);
        }
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    protected function checkAuth()
    {
        return Auth::check();
    }

    /**
     * Obtenir l'utilisateur connecté
     */
    protected function getAuthenticatedUser()
    {
        return Auth::user();
    }
}
