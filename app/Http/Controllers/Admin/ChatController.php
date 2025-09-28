<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        $messages = ChatMessage::with('user')->latest()->take(200)->get()->reverse();
        return view('admin.chat', compact('messages'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|max:2000',
        ]);
        ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $data['message'],
        ]);
        return back();
    }

    public function destroy(ChatMessage $message)
    {
        if ($message->user_id !== Auth::id() && optional(Auth::user())->role !== 'admin') {
            abort(403);
        }
        $message->delete();
        return back();
    }
}
