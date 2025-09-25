<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(20);
        return view('pages.notifications', compact('notifications'));
    }

    public function markRead(string $id)
    {
        $n = Auth::user()->notifications()->findOrFail($id);
        $n->markAsRead();
        return back();
    }

    public function markAll()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back();
    }
}
