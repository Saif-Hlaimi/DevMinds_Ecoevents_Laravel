<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class InspirationService
{
    public function suggest(string $prompt, string $context = ''): string
    {
        $cfg = config('services.gemini');
        $apiKey = $cfg['api_key'];
        $model = $cfg['model'];
        $endpoint = rtrim($cfg['endpoint'], '/');

        if (!$apiKey) {
            return 'Inspiration service not configured.';
        }
        // Gemini generateContent
        $url = sprintf('%s/%s:generateContent?key=%s', $endpoint, $model, urlencode($apiKey));
        $payload = [
            'contents' => [[
                'parts' => [[ 'text' => "You are a creative assistant. Context: $context\nPrompt: $prompt\nReturn short, original suggestions only." ]]
            ]]
        ];
        $res = Http::timeout(20)->asJson()->post($url, $payload);
        if (!$res->ok()) return 'No suggestion available now.';
        $data = $res->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
        return trim($text) ?: 'No suggestion available.';
    }
}
