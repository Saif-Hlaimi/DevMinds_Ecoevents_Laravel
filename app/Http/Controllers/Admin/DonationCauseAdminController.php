<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationCauseRequest;
use App\Http\Requests\UpdateDonationCauseRequest;
use App\Models\DonationCause;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonationCauseAdminController extends Controller
{
    public function index()
    {
        $donationCauses = DonationCause::latest()->paginate(15);
        return view('admin.donation-causes.donation-causes', compact('donationCauses'));
    }

    public function donations(DonationCause $donationCause, Request $request)
    {
        $query = $donationCause->donations()->with('user');

        $sort = $request->get('sort');

        switch ($sort) {
            case 'amount_asc':
                $query->orderBy('amount', 'asc');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'm asc');
                break;
            default:
                $query->latest(); 
                break;
        }

        $donations = $query->paginate(15);
        return view('admin.donation-causes.donations', compact('donationCause', 'donations'));
    }

    public function create()
    {
        return view('admin.donation-causes.create');
    }

    public function generateImage(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required|string|max:1000',
            ]);

            $description = $request->description;
            $apiKey = env('STABILITY_API_KEY');
            if (!$apiKey) {
                return response()->json(['error' => 'Stability AI API key not configured in .env'], 500);
            }

            $prompt = "Eco-friendly donation cause illustration based on: " . $description . ". High quality, realistic, vibrant colors, environmental theme, 1024x1024 resolution";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.stability.ai/v1/generation/stable-diffusion-xl-1024-v1-0/text-to-image', [
                'text_prompts' => [
                    [
                        'text' => $prompt,
                    ],
                ],
                'cfg_scale' => 7,
                'height' => 1024,
                'width' => 1024,
                'samples' => 1,
                'steps' => 30,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $artifacts = $data['artifacts'][0] ?? null;
                if ($artifacts && isset($artifacts['base64'])) {
                    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $artifacts['base64']));
                    $fileName = 'generated_' . Str::random(10) . '.png';
                    $path = 'images/generated/' . $fileName;
                    Storage::disk('public')->put($path, $imageData);

                    return response()->json([
                        'success' => true,
                        'image_url' => asset('storage/' . $path),
                        'file_path' => $path,
                    ]);
                } else {
                    return response()->json(['error' => 'No image data in response'], 500);
                }
            } else {
                Log::error('Stability AI Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return response()->json(['error' => 'API request failed: ' . $response->status() . ' - ' . $response->body()], 500);
            }
        } catch (\Exception $e) {
            Log::error('Generate Image Exception', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Internal error: ' . $e->getMessage()], 500);
        }
    }

   public function generateDescription(Request $request)
{
    try {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $title = $request->title;
        $apiKey = env('DEEPINFRA_API_KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'DeepInfra API key not configured in .env'], 500);
        }

        $prompt = "Generate a full, standalone, compelling, eco-friendly description for a donation cause titled: '" . $title . "'. Start with an engaging opening sentence, do not start with 'and'. Make it 100-200 words, inspiring, focused on environmental impact, and include calls to action for donations. End with hashtags like #ProtectTheNature #SaveOurPlanet. Structure it as a complete paragraph without repetition of the prompt.";

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.deepinfra.com/v1/inference/mistralai/Mistral-7B-Instruct-v0.1', [
            'input' => $prompt,
            'parameters' => [
                'temperature' => 0.7,
                'max_new_tokens' => 400, // Increased for full content
                'top_p' => 0.9,
                'do_sample' => true,
            ],
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $generatedText = $data['results'][0]['generated_text'] ?? null;
            if ($generatedText) {
                // Clean up: Remove prompt repetition and ensure standalone
                $description = trim($generatedText);
                // Remove if it starts with the prompt
                $promptPrefix = substr($prompt, 0, 100);
                if (strpos($description, $promptPrefix) === 0) {
                    $description = trim(substr($description, strlen($promptPrefix)));
                }
                // Clean any artifacts
                $description = preg_replace('/^\s*and\s*/i', '', $description); // Remove leading "and"
                $description = preg_replace('/\s+and\s+description.*$/i', '', $description); // Remove trailing prompt

                // Truncate to 999 characters if longer
                if (strlen($description) > 999) {
                    $description = substr($description, 0, 999) . '...';
                }

                return response()->json([
                    'success' => true,
                    'description' => $description,
                    'length' => strlen($description), // Optional: for debugging
                ]);
            } else {
                return response()->json(['error' => 'No generated text in response'], 500);
            }
        } else {
            Log::error('DeepInfra Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return response()->json(['error' => 'API request failed: ' . $response->status() . ' - ' . $response->body()], 500);
        }
    } catch (\Exception $e) {
        Log::error('Generate Description Exception', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Internal error: ' . $e->getMessage()], 500);
    }
}

    public function store(StoreDonationCauseRequest $request)
    {
        $data = $request->validated();
        $imagePath = $request->input('generated_image_path');
        if ($imagePath) {
            $data['image'] = $imagePath;
        } elseif ($request->hasFile('image')) {
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
        $imagePath = $request->input('generated_image_path');
        if ($imagePath) {
            $data['image'] = $imagePath;
        } elseif ($request->hasFile('image')) {
            // Delete old image if exists
            if ($donationCause->image) {
                Storage::disk('public')->delete($donationCause->image);
            }
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