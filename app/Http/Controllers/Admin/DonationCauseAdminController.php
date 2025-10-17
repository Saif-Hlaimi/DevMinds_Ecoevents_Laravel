<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationCauseRequest;
use App\Http\Requests\UpdateDonationCauseRequest;
use App\Models\DonationCause;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationCauseAdminController extends Controller
{
    public function index()
    {
        $donationCauses = DonationCause::latest()->paginate(15);
        return view('admin.donation-causes.donation-causes', compact('donationCauses'));
    }

    public function donations(DonationCause $donationCause)
    {
        $donations = $donationCause->donations()->with('user')->latest()->paginate(15);
        return view('admin.donation-causes.donations', compact('donationCause', 'donations'));
    }

    public function create()
    {
        return view('admin.donation-causes.create');
    }

    public function store(StoreDonationCauseRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        DonationCause::create($data);
        return redirect()->route('dashboard.admin.donation-causes.donation-causes')->with('success', 'Donation cause created!');
    }

    public function edit(DonationCause $donationCause)
    {
        return view('admin.donation-causes.edit', compact('donationCause'));
    }

    public function update(UpdateDonationCauseRequest $request, DonationCause $donationCause)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        $donationCause->update($data);
        return redirect()->route('dashboard.admin.donation-causes.donation-causes')->with('success', 'Donation cause updated!');
    }

    public function destroy(DonationCause $donationCause)
    {
        $donationCause->delete();
        return back()->with('success', 'Donation cause deleted!');
    }

    public function destroyDonation(DonationCause $donationCause, Donation $donation)
    {
        // Verify the donation belongs to the cause
        if ($donation->donation_cause_id !== $donationCause->id) {
            return back()->with('error', 'Donation does not belong to this cause.');
        }

        // Subtract from raised amount
        $donationCause->raised_amount -= $donation->amount;
        $donationCause->save();

        $donation->delete();

        return redirect()->route('dashboard.admin.donation-causes.donations', $donationCause->id)
            ->with('success', 'Donation deleted successfully!');
    }
}