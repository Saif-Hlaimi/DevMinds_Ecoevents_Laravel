<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Affichage profil
    public function show(Request $request)
    {
        $user = $request->user();
        return view('pages.profile', compact('user'));
    }

    // Mise à jour profil
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'country'       => ['nullable', 'string', 'max:100'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'role'          => ['sometimes', 'in:user,admin'],
        ]);

        // Gestion image
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $validated['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        // Protection rôle
        if ($request->has('role') && $user->role !== 'admin') {
            unset($validated['role']);
        }

        $user->update($validated);

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    // Supprimer profil
    public function destroy(Request $request)
    {
        $user = $request->user();

        if ($user->profile_image) {
            Storage::disk('public')->delete($user->profile_image);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your profile has been deleted.');
    }
}
