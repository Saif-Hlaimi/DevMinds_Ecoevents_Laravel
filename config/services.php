<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | Ce fichier stocke les identifiants pour les services tiers comme Mailgun,
    | Postmark, AWS, etc. Il sert de point central pour ces informations.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    // --- Services externes personnalisés ---
    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-1.5-flash'),
        'endpoint' => env('GEMINI_ENDPOINT', 'https://generativelanguage.googleapis.com/v1beta/models'),
    ],
    'azure_moderator' => [
        'key' => env('AZURE_CONTENT_MODERATOR_KEY'),
        'endpoint' => env('AZURE_CONTENT_MODERATOR_ENDPOINT'),
        'language' => env('AZURE_CONTENT_MODERATOR_LANGUAGE', 'eng'),
    ],
    'azure_speech' => [
        'key' => env('AZURE_SPEECH_KEY'),
        'region' => env('AZURE_SPEECH_REGION'),
        'endpoint' => env('AZURE_SPEECH_ENDPOINT'),
        'voice' => env('AZURE_SPEECH_VOICE', 'en-US-JennyNeural'),
        'audio_format' => env('AZURE_SPEECH_AUDIO_FORMAT', 'audio-16khz-32kbitrate-mono-mp3'),
    ],

    'assemblyai' => [
        'api_key' => env('ASSEMBLYAI_API_KEY'),
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

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    // moved above; avoid duplicate key
    'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
    'currency' => env('STRIPE_CURRENCY', 'usd'),
],
    'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
    ],


];