<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationCause;
use App\Http\Requests\StoreDonationRequest;
use App\Http\Requests\UpdateDonationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class DonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donations = Donation::with(['user', 'donationCause'])->latest()->paginate(10);
        return view('pages.donations.index', compact('donations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $donationCauses = DonationCause::all();
        return view('pages.donations.create', compact('donationCauses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDonationRequest $request)
    {
        $donationCause = DonationCause::find($request->donation_cause_id);
        $donationAmount = $request->amount;

        if ($request->payment_method === 'test') {
            return $this->handleTestDonation($request, $donationCause);
        } elseif ($request->payment_method === 'stripe') {
            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => $donationAmount * 100, // Amount in cents
                    'currency' => 'usd',
                    'payment_method' => $request->payment_method_id,
                    'confirmation_method' => 'manual',
                    'confirm' => true,
                    'return_url' => route('donation-causes.show', $donationCause->id), // Optional, for 3D Secure
                ]);

                if ($paymentIntent->status === 'succeeded') {
                    // Create donation on success
                    $donation = Donation::create([
                        'user_id' => Auth::id(),
                        'amount' => $donationAmount,
                        'donation_cause_id' => $request->donation_cause_id,
                    ]);

                    // Update raised amount with cap
                    $donationCause->raised_amount = min($donationCause->raised_amount + $donationAmount, $donationCause->goal_amount);
                    $donationCause->save();

                    return redirect()->route('donation-causes.show', $donationCause->id)
                        ->with('success', 'Thank you! Your donation of $' . number_format($donationAmount, 2) . ' has been successfully processed via Stripe.');
                } else {
                    return back()->with('error', 'Payment failed: ' . ($paymentIntent->last_payment_error->message ?? 'Unknown error.'));
                }
            } catch (\Exception $e) {
                \Log::error('Stripe PaymentIntent error: ' . $e->getMessage(), ['request' => $request->all()]);
                return back()->with('error', 'An error occurred during payment. Please try again.');
            }
        }

        // Default to test
        return $this->handleTestDonation($request, $donationCause);
    }

    private function handleTestDonation($request, $donationCause)
    {
        $donationAmount = $request->amount;

        $donation = Donation::create([
            'user_id' => Auth::id(),
            'amount' => $donationAmount,
            'donation_cause_id' => $request->donation_cause_id,
        ]);

        // Update raised amount with cap
        $donationCause->raised_amount = min($donationCause->raised_amount + $donationAmount, $donationCause->goal_amount);
        $donationCause->save();

        return redirect()->route('donation-causes.show', $donationCause->id)->with('success', 'Donation created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        $donation->load(['user', 'donationCause']);
        return view('pages.donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        $this->authorize('update', $donation);
        $donationCauses = DonationCause::all();
        return view('pages.donations.edit', compact('donation', 'donationCauses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDonationRequest $request, Donation $donation)
    {
        $this->authorize('update', $donation);

        $oldAmount = $donation->amount;
        $donation->update($request->validated());

        // Adjust raised amount in donation cause if amount changed, with cap if increasing
        $donationCause = $donation->donationCause;
        $delta = $donation->amount - $oldAmount;
        if ($delta > 0) {
            $donationCause->raised_amount = min($donationCause->raised_amount + $delta, $donationCause->goal_amount);
        } else {
            $donationCause->raised_amount += $delta;
        }
        $donationCause->save();

        return redirect()->route('donations.index')->with('success', 'Donation updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation);

        // Subtract from raised amount
        $donationCause = $donation->donationCause;
        $donationCause->raised_amount -= $donation->amount;
        $donationCause->save();

        $donation->delete();

        return redirect()->route('donations.index')->with('success', 'Donation deleted successfully!');
    }
}