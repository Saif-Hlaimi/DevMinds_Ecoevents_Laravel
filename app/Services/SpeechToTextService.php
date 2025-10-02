<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SpeechToTextService
{
    private string $base = 'https://api.assemblyai.com/v2';

    public function transcribeFromBytes(string $bytes, string $mime = 'audio/webm', ?string $language = null): array
    {
        $key = config('services.assemblyai.api_key');
        if (!$key) {
            return ['ok' => false, 'error' => 'ASSEMBLYAI_API_KEY not configured'];
        }

        try {
            // 1) Upload bytes to AssemblyAI upload endpoint (streaming upload)
            $uploadUrl = $this->base.'/upload';
            if (config('app.debug')) Log::info('[STT] Uploading audio', ['bytes' => strlen($bytes), 'mime' => $mime]);
            $resUpload = Http::withHeaders([
                'authorization' => $key,
                'Transfer-Encoding' => 'chunked',
                'Content-Type' => $mime,
            ])->timeout(60)->withBody($bytes, $mime)->post($uploadUrl);
            if (!$resUpload->ok()) {
                if (config('app.debug')) Log::warning('[STT] Upload failed', ['status' => $resUpload->status(), 'body' => $resUpload->body()]);
                return ['ok' => false, 'error' => 'Upload failed'];
            }
            $uploadData = $resUpload->json();
            $audioUrl = $uploadData['upload_url'] ?? null;
            if (!$audioUrl) return ['ok' => false, 'error' => 'No upload_url'];

            // 2) Create transcript
            $createUrl = $this->base.'/transcript';
            $payload = [ 'audio_url' => $audioUrl ];
            // Language handling: if provided (e.g., en-US, fr-FR, ar-SA), map to AssemblyAI code (en, fr, ar)
            if ($language) {
                $code = $this->mapLocaleToAai($language);
                if ($code === 'auto') {
                    $payload['language_detection'] = true;
                } elseif ($code) {
                    $payload['language_code'] = $code;
                }
            }
            if (config('app.debug')) Log::info('[STT] Creating transcript');
            $resCreate = Http::withHeaders([
                'authorization' => $key,
                'content-type' => 'application/json'
            ])->timeout(30)->post($createUrl, $payload);
            if (!$resCreate->ok()) {
                if (config('app.debug')) Log::warning('[STT] Create transcript failed', ['status' => $resCreate->status(), 'body' => $resCreate->body()]);
                return ['ok' => false, 'error' => 'Create transcript failed'];
            }
            $createData = $resCreate->json();
            $id = $createData['id'] ?? null;
            if (!$id) return ['ok' => false, 'error' => 'No transcript id'];

            // 3) Poll for completion
            $pollUrl = $this->base.'/transcript/'.$id;
            $started = microtime(true);
            $deadline = $started + 120; // 2 minutes max
            while (true) {
                usleep(800000); // 0.8s
                if (microtime(true) > $deadline) {
                    return ['ok' => false, 'error' => 'Transcription timeout'];
                }
                $resPoll = Http::withHeaders(['authorization' => $key])->timeout(15)->get($pollUrl);
                if (!$resPoll->ok()) {
                    if (config('app.debug')) Log::warning('[STT] Poll failed', ['status' => $resPoll->status(), 'body' => $resPoll->body()]);
                    continue;
                }
                $pollData = $resPoll->json();
                $status = $pollData['status'] ?? '';
                if ($status === 'completed') {
                    $text = $pollData['text'] ?? '';
                    if (config('app.debug')) Log::info('[STT] Completed', ['chars' => strlen($text)]);
                    return ['ok' => true, 'text' => $text];
                }
                if ($status === 'error') {
                    $err = $pollData['error'] ?? 'unknown error';
                    if (config('app.debug')) Log::warning('[STT] Error', ['error' => $err]);
                    return ['ok' => false, 'error' => $err];
                }
            }
        } catch (\Throwable $e) {
            if (config('app.debug')) Log::error('[STT] Exception', ['message' => $e->getMessage()]);
            return ['ok' => false, 'error' => 'Exception: '.$e->getMessage()];
        }
    }
    
    private function mapLocaleToAai(string $locale): ?string
    {
        $l = strtolower($locale);
        if ($l === 'auto') return 'auto';
        if (str_starts_with($l, 'en')) return 'en';
        if (str_starts_with($l, 'fr')) return 'fr';
        if (str_starts_with($l, 'ar')) return 'ar';
        // Add more mappings if needed
        return null;
    }
}
