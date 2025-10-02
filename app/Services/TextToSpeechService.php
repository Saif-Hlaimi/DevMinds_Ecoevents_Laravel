<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TextToSpeechService
{
    private function issueToken(string $region, string $key): ?string
    {
        try {
            $tokenUrl = 'https://'.$region.'.api.cognitive.microsoft.com/sts/v1.0/issuetoken';
            if (config('app.debug')) {
                Log::info('[TTS] Issuing token', ['url' => $tokenUrl, 'region' => $region]);
            }
            $res = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $key,
                'Content-Length' => '0',
                'User-Agent' => 'EcoEvents-Groups-TTS'
            ])->timeout(15)->post($tokenUrl);
            if (!$res->ok()) {
                if (config('app.debug')) Log::warning('[TTS] Token request failed', ['status' => $res->status(), 'body' => $res->body()]);
                return null;
            }
            return trim($res->body());
        } catch (\Throwable $e) {
            if (config('app.debug')) Log::error('[TTS] Token exception', ['message' => $e->getMessage()]);
            return null;
        }
    }

    public function synthesize(string $text, ?string $voice = null): string
    {
        $text = trim($text);
        if ($text === '') return '';
        $cfg = config('services.azure_speech');
        $key = $cfg['key'] ?? null;
        $region = $cfg['region'] ?? null;
        $endpoint = rtrim($cfg['endpoint'] ?? '', '/');
        $voice = $voice ?: ($cfg['voice'] ?? 'en-US-JennyNeural');
        $format = $cfg['audio_format'] ?? 'audio-16khz-32kbitrate-mono-mp3';
        if (!$key) return '';

        // Resolve correct Azure TTS endpoint
        // For multi-service Cognitive Services, try regional TTS endpoint
        $url = '';
        if (!empty($region)) {
            // Use regional Speech endpoint (works for both dedicated and multi-service)
            $url = 'https://'.$region.'.tts.speech.microsoft.com/cognitiveservices/v1';
        } elseif (!empty($endpoint)) {
            // Fallback: try to construct from provided endpoint
            $url = rtrim($endpoint, '/').'/cognitiveservices/v1';
        }
        if ($url === '') return '';

        // Azure Neural TTS via REST (SSML with proper XML declaration)
        // Extract locale from voice name (e.g., en-US-JennyNeural -> en-US)
        $locale = 'en-US';
        if (preg_match('/^([a-z]{2}-[A-Z]{2})/', $voice, $m)) {
            $locale = $m[1];
        }
        $escapedText = $this->escape($text);
        $ssml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<speak version="1.0" xmlns="http://www.w3.org/2001/10/synthesis" xml:lang="'.$locale.'">'
            . '<voice name="'.$voice.'">'
            . $escapedText
            . '</voice>'
            . '</speak>';

        try {
            if (config('app.debug')) {
                Log::info('[TTS] Requesting synthesis', [
                    'url' => $url,
                    'region' => $region,
                    'voice' => $voice,
                    'format' => $format,
                    'text_len' => strlen($text),
                    'ssml_preview' => substr($ssml, 0, 200)
                ]);
            }
            // Use direct subscription key (no token for multi-service)
            $headers = [
                'Ocp-Apim-Subscription-Key' => $key,
                // Region header is harmless on regional endpoint and required on some api.cognitive endpoints
                'Ocp-Apim-Subscription-Region' => $region ?? '',
                'Content-Type' => 'application/ssml+xml',
                'Accept' => 'audio/mpeg',
                'X-Microsoft-OutputFormat' => $format,
                'User-Agent' => 'EcoEvents-Groups-TTS'
            ];
            $res = Http::withHeaders($headers)->timeout(30)->withBody($ssml, 'application/ssml+xml')->post($url);
            if (!$res->ok()) {
                if (config('app.debug')) {
                    $body = $res->body();
                    Log::warning('[TTS] Synthesis failed', [
                        'status' => $res->status(),
                        'content_type' => $res->header('Content-Type'),
                        'body_raw' => $body,
                        'body_length' => strlen($body),
                        'x_request_id' => $res->header('X-Request-Id'),
                        'ssml_sent' => substr($ssml, 0, 500),
                    ]);
                }
                // Retry once with a more common MP3 format
                $altFormat = 'audio-24khz-48kbitrate-mono-mp3';
                if ($format !== $altFormat) {
                    if (config('app.debug')) Log::info('[TTS] Retry with alt format', ['format' => $altFormat]);
                    $headers['X-Microsoft-OutputFormat'] = $altFormat;
                    $res2 = Http::withHeaders($headers)->timeout(30)->withBody($ssml, 'application/ssml+xml')->post($url);
                    if ($res2->ok()) {
                        $audio2 = $res2->body();
                        if (config('app.debug')) Log::info('[TTS] Synthesis success (alt format)', ['bytes' => strlen($audio2)]);
                        return base64_encode($audio2);
                    } else {
                        if (config('app.debug')) Log::warning('[TTS] Alt format failed', ['status' => $res2->status(), 'body' => $res2->body()]);
                    }
                }

                // Bearer token fallback (some configurations require token auth)
                $token = $this->issueToken($region ?? 'eastus', $key);
                if ($token) {
                    if (config('app.debug')) Log::info('[TTS] Retry with bearer token');
                    $headersToken = [
                        'Authorization' => 'Bearer '.$token,
                        'Content-Type' => 'application/ssml+xml',
                        'Accept' => 'audio/mpeg',
                        'X-Microsoft-OutputFormat' => $format,
                        'User-Agent' => 'EcoEvents-Groups-TTS'
                    ];
                    $res3 = Http::withHeaders($headersToken)->timeout(30)->withBody($ssml, 'application/ssml+xml')->post($url);
                    if ($res3->ok()) {
                        $audio3 = $res3->body();
                        if (config('app.debug')) Log::info('[TTS] Synthesis success (bearer)', ['bytes' => strlen($audio3)]);
                        return base64_encode($audio3);
                    } else {
                        if (config('app.debug')) Log::warning('[TTS] Bearer retry failed', ['status' => $res3->status(), 'body' => $res3->body()]);
                    }
                }

                // Safe voice fallback (voice might be unavailable in region)
                $safeVoice = 'en-US-AriaNeural';
                if ($voice !== $safeVoice) {
                    if (config('app.debug')) Log::info('[TTS] Retry with safe voice', ['voice' => $safeVoice]);
                    $safeLocale = 'en-US';
                    $ssmlSafe = '<?xml version="1.0" encoding="UTF-8"?>'
                        . '<speak version="1.0" xmlns="http://www.w3.org/2001/10/synthesis" xml:lang="'.$safeLocale.'">'
                        . '<voice name="'.$safeVoice.'">'
                        . $escapedText
                        . '</voice>'
                        . '</speak>';
                    $res4 = Http::withHeaders($headers)->timeout(30)->withBody($ssmlSafe, 'application/ssml+xml')->post($url);
                    if ($res4->ok()) {
                        $audio4 = $res4->body();
                        if (config('app.debug')) Log::info('[TTS] Synthesis success (safe voice)', ['bytes' => strlen($audio4)]);
                        return base64_encode($audio4);
                    } else {
                        if (config('app.debug')) Log::warning('[TTS] Safe voice failed', ['status' => $res4->status(), 'body' => $res4->body()]);
                    }
                }

                // Plain text fallback with Synthesis-VoiceName header
                if (config('app.debug')) Log::info('[TTS] Retry with plaintext synthesis header');
                $plainHeaders = [
                    'Ocp-Apim-Subscription-Key' => $key,
                    'Ocp-Apim-Subscription-Region' => $region ?? '',
                    'Content-Type' => 'text/plain',
                    'Accept' => 'audio/mpeg',
                    'X-Microsoft-OutputFormat' => $format,
                    'Synthesis-VoiceName' => $voice,
                    'User-Agent' => 'EcoEvents-Groups-TTS'
                ];
                $res5 = Http::withHeaders($plainHeaders)->timeout(30)->withBody($text, 'text/plain')->post($url);
                if ($res5->ok()) {
                    $audio5 = $res5->body();
                    if (config('app.debug')) Log::info('[TTS] Synthesis success (plaintext)', ['bytes' => strlen($audio5)]);
                    return base64_encode($audio5);
                } else {
                    if (config('app.debug')) Log::warning('[TTS] Plaintext failed', ['status' => $res5->status(), 'body' => $res5->body()]);
                }
                return '';
            }
            $audio = $res->body();
            if (config('app.debug')) {
                Log::info('[TTS] Synthesis success', [ 'bytes' => strlen($audio) ]);
            }
            return base64_encode($audio);
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                Log::error('[TTS] Exception', [ 'message' => $e->getMessage(), 'trace' => $e->getTraceAsString() ]);
            }
            return '';
        }
    }

    private function escape(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE | ENT_XML1, 'UTF-8');
    }
}
