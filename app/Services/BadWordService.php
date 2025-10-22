<?php

namespace App\Services;

class BadWordService
{
    public static function contains(string $text): bool
    {
        $badWords = config('badwords');
        $textLower = strtolower($text);

        foreach ($badWords as $word) {
            if (strpos($textLower, strtolower($word)) !== false) {
                return true;
            }
        }

        return false;
    }

    public static function clean(string $text, string $fillText = '[censuré]'): string
    {
        $badWords = config('badwords');

        foreach ($badWords as $word) {
            $text = preg_replace('/\b' . preg_quote($word, '/') . '\b/i', $fillText, $text);
        }

        return $text;
    }
}
