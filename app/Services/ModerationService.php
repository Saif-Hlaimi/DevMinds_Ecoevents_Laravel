<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModerationService
{
    public function hasBadWords(?string $text): bool
    {
        $text = trim((string)$text);
        if ($text === '') return false;
        $endpoint = rtrim((string)config('services.azure_moderator.endpoint'), '/');
        $key = (string)config('services.azure_moderator.key');
        if (!$endpoint || !$key) {
            // Fail-open: if not configured, don't block content
            return false;
        }
        // Using Azure Content Moderator-like endpoint for text screening
        $url = $endpoint.'/contentmoderator/moderate/v1.0/ProcessText/Screen?language='.urlencode(config('services.azure_moderator.language','eng')).'&classify=True';
        try {
            $res = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $key,
                'Accept' => 'application/json'
            ])->timeout(15)->withBody($text, 'text/plain')->post($url);
            if ($res->ok()) {
                $data = $res->json();
                // Azure returns Terms array when bad terms found
                $terms = $data['Terms'] ?? [];
                if (is_array($terms) && count($terms) > 0) return true;
                // If classified categories indicate offensive content
                $classification = $data['Classification'] ?? null;
                if (is_array($classification)) {
                    $cat1 = $classification['Category1']['Score'] ?? 0; // sexually explicit content
                    $cat2 = $classification['Category2']['Score'] ?? 0; // sexually suggestive or mature
                    $cat3 = $classification['Category3']['Score'] ?? 0; // offensive language
                    if (max($cat1,$cat2,$cat3) >= 0.6) return true; // heuristic threshold
                }
                return false;
            }
            if (config('app.debug')) Log::warning('[MOD] CM request failed', ['status'=>$res->status(), 'body'=>$res->body()]);
        } catch (\Throwable $e) {
            if (config('app.debug')) Log::error('[MOD] CM exception', ['msg'=>$e->getMessage()]);
        }

        // Fallback to Azure Content Safety Text Analyze (if available on this resource)
        try {
            $csUrl = $endpoint.'/contentsafety/text:analyze?api-version=2023-10-01';
            $payload = [
                'text' => $text,
                'categories' => ['Hate','Sexual','Violence','SelfHarm'],
            ];
            $res2 = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ])->timeout(15)->post($csUrl, $payload);
            if ($res2->ok()) {
                $data2 = $res2->json();
                $analyses = $data2['categoriesAnalysis'] ?? [];
                foreach ($analyses as $an) {
                    $severity = $an['severity'] ?? 0; // 0-7
                    if ($severity >= 2) return true; // moderate threshold
                }
                return false;
            } else {
                if (config('app.debug')) Log::warning('[MOD] CS request failed', ['status'=>$res2->status(), 'body'=>$res2->body()]);
            }
        } catch (\Throwable $e) {
            if (config('app.debug')) Log::error('[MOD] CS exception', ['msg'=>$e->getMessage()]);
        }

        // Fail-open if both checks failed
        return false;
    }
}
