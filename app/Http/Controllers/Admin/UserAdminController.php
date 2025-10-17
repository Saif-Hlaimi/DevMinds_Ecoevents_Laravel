<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
public function index()
{
    $users = User::latest()->paginate(15);

    // Statistiques : nombre d'utilisateurs par rôle
    $roleStats = User::selectRaw('role, COUNT(*) as count')
        ->groupBy('role')
        ->pluck('count', 'role');

    return view('admin.users', [
        'users' => $users,
        'roleStats' => $roleStats,
    ]);
}
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
        ]);
        $user->update($data);
        return back()->with('success', 'User updated');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors('You cannot delete yourself.');
        }
        $user->delete();
        return back()->with('success', 'User deleted');
    }
    
}
