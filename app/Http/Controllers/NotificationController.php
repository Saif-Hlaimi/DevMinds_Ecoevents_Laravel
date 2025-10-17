<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Afficher toutes les notifications de l'utilisateur connecté.
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('pages.notifications', compact('notifications'));
    }

    /**
     * Marquer une notification comme lue.
     */
    public function markRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'Notification marked as read.');
    }

    /**
     * Marquer toutes les notifications comme lues.
     */
    public function markAll()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Supprimer une notification spécifique.
     */
    public function destroy(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notification deleted successfully.');
    }

    /**
     * Supprimer toutes les notifications.
     */
    public function clearAll()
    {
        Auth::user()->notifications()->delete();
        return back()->with('success', 'All notifications cleared.');
    }
}
