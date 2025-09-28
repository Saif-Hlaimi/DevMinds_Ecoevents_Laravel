<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailMessage;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    public function index()
    {
        $emails = EmailMessage::latest()->paginate(10);
        return view('admin.email', compact('emails'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from_email' => 'required|email',
            'to_email' => 'required|email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        EmailMessage::create($data);
        return back()->with('success', 'Email queued (saved).');
    }

    public function markRead(EmailMessage $email)
    {
        $email->update(['is_read' => true]);
        return back();
    }

    public function destroy(EmailMessage $email)
    {
        $email->delete();
        return back()->with('success', 'Email deleted');
    }
}
