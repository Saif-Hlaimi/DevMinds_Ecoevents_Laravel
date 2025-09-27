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
        return view('pages.donation-causes.show', compact('donationCause'));
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