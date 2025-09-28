<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationAdminController extends Controller
{
    public function index()
    {
        $donations = Donation::with(['user','donationCause'])->latest()->paginate(15);
        return view('admin.donations', compact('donations'));
    }

    public function destroy(Donation $donation)
    {
        $donation->delete();
        return back()->with('success', 'Donation deleted');
    }
}
