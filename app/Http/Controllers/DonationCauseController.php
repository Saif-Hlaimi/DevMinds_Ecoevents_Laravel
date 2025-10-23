<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationCauseRequest;
use App\Http\Requests\UpdateDonationCauseRequest;
use App\Models\DonationCause;
use Illuminate\Http\Request;

class DonationCauseController extends Controller
{
    public function index()
    {
        $donationCauses = DonationCause::all();
        return view('pages.donation-causes.index', compact('donationCauses'));
    }

    public function create()
    {
        return view('pages.donation-causes.create');
    }

    public function store(StoreDonationCauseRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        DonationCause::create($data);
        return redirect()->route('donation-causes.index')->with('status', 'Donation cause created!');
    }

    public function show(DonationCause $donationCause)
    {
        // Compute remaining amount safely to avoid negative values
        $goal = (float) ($donationCause->goal_amount ?? 0);
        $raised = (float) ($donationCause->raised_amount ?? 0);
        $remaining = max(0, $goal - $raised);

        // Also compute percentage for convenient use in the view if needed
        $percentage = $goal > 0 ? min(($raised / $goal) * 100, 100) : 0;

        return view('pages.donation-causes.show', compact('donationCause', 'remaining', 'percentage'));
    }

    public function edit(DonationCause $donationCause)
    {
        return view('pages.donation-causes.edit', compact('donationCause'));
    }

    public function update(UpdateDonationCauseRequest $request, DonationCause $donationCause)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('images', 'public');
        }
        $donationCause->update($data);
        return redirect()->route('donation-causes.index')->with('status', 'Donation cause updated!');
    }

    public function destroy(DonationCause $donationCause)
    {
        $donationCause->delete();
        return redirect()->route('donation-causes.index')->with('status', 'Donation cause deleted!');
    }
}