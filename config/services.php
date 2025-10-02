<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    // --- Custom external services ---
    'gemini' => [
        // Google Generative Language API (Gemini)
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        'endpoint' => env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models'),
    ],
    'azure_moderator' => [
        // Azure Content Moderator or text screening compatible endpoint
        'key' => env('AZURE_CONTENT_MODERATOR_KEY'),
        'endpoint' => env('AZURE_CONTENT_MODERATOR_ENDPOINT'), // e.g., https://<resource>.cognitiveservices.azure.com
        'language' => env('AZURE_CONTENT_MODERATOR_LANGUAGE', 'eng'),
    ],
    'azure_speech' => [
        // Azure Speech (Text-to-Speech)
        'key' => env('AZURE_SPEECH_KEY'),
        'region' => env('AZURE_SPEECH_REGION'), // e.g., francecentral
        'endpoint' => env('AZURE_SPEECH_ENDPOINT'), // e.g., https://francecentral.api.cognitive.microsoft.com/
        'voice' => env('AZURE_SPEECH_VOICE', 'en-US-JennyNeural'),
        'audio_format' => env('AZURE_SPEECH_AUDIO_FORMAT', 'audio-16khz-32kbitrate-mono-mp3'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_REDIRECTS'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ],
];