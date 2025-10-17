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
use Square\SquareClient;
use Square\Environments;
use Square\Payments\Requests\CreatePaymentRequest; // Fixed: Correct namespace
use Square\Types\Money;
use Square\Types\Currency;

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
            return $this->handleStripeDonation($request, $donationCause);
        } elseif ($request->payment_method === 'square') {
            return $this->handleSquareDonation($request, $donationCause);
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

    private function handleStripeDonation($request, $donationCause)
    {
        $donationAmount = $request->amount;

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

private function handleSquareDonation($request, $donationCause)
    {
        $donationAmount = $request->amount;

        // Client initialization (unchanged)
        $client = new SquareClient(
            token: env('SQUARE_ACCESS_TOKEN'),
            options: [
                'baseUrl' => (env('SQUARE_ENVIRONMENT') === 'production' 
                    ? Environments::Production->value 
                    : Environments::Sandbox->value),
            ]
        );

        // API access (unchanged)
        $paymentsApi = $client->payments;

        try {
            // Create request with type-safe currency
            $createPaymentRequest = new CreatePaymentRequest([
                'idempotencyKey' => uniqid(), // Prevent duplicate payments
                'sourceId' => $request->payment_source_id, // Nonce from Square Web Payments SDK
                'amountMoney' => new Money([
                    'amount' => (int) ($donationAmount * 100), // In cents
                    'currency' => Currency::Usd->value, // Type-safe 'USD'
                ]),
            ]);

            // API call (unchanged)
            $response = $paymentsApi->create(request: $createPaymentRequest);

            // Fixed: Direct access to getPayment() (no getResult())
            if ($response->getPayment() !== null) {
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
                    ->with('success', 'Thank you! Your donation of $' . number_format($donationAmount, 2) . ' has been successfully processed via Square.');
            } else {
                return back()->with('error', 'Payment failed: No payment returned.');
            }
        } catch (\Square\Exceptions\SquareApiException $e) {
            // Refined: Use getMessage() for Square API errors
            $errorMessage = $e->getMessage();
            \Log::error('Square Payment error: ' . $errorMessage, ['request' => $request->all()]);
            return back()->with('error', 'Payment failed: ' . $errorMessage);
        } catch (\Exception $e) {
            \Log::error('Square Payment error: ' . $e->getMessage(), ['request' => $request->all()]);
            return back()->with('error', 'An error occurred during payment. Please try again.');
        }
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