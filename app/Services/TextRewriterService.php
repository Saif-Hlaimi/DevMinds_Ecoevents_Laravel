<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TextRewriterService
{
    protected $url;

    public function __construct()
    {
        $this->url = config('services.text_rewriter.url');
    }

    /**
     * Améliore un texte via l'API Flask.
     */
    public function rewrite(string $text): string
    {
        try {
            $response = Http::post($this->url, ['text' => $text]);
            if ($response->ok() && isset($response['rewritten'])) {
                return $response['rewritten'];
            }
        } catch (\Exception $e) {
            // Log l'erreur si nécessaire
            \Log::error('TextRewriterService error: '.$e->getMessage());
        }

        // Retourne le texte original si échec
        return $text;
    }
}
    