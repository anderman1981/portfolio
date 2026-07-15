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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'gemini' => [
        'key' => env('GEMINI_API_KEY'),
        // Multi-key failover (primary first). Add more slots as needed.
        'keys' => array_filter([
            env('GEMINI_API_KEY'),
            env('GEMINI_API_KEY_2'),
            env('GEMINI_API_KEY_3'),
            env('GEMINI_API_KEY_4'),
        ]),
    ],

    'github' => [
        'token' => env('GITHUB_TOKEN'),
        'username' => env('GITHUB_USERNAME', 'anderman1981'),
    ],

    'cv_bridge' => [
        'webhook' => env('CV_BRIDGE_WEBHOOK'),      // n8n webhook URL (ida)
        'token' => env('CV_BRIDGE_TOKEN'),          // secreto compartido para el reply-intake (vuelta)
        'channel' => env('SLACK_CHANNEL_CV', 'cv'), // etiqueta de canal
        'slack_webhook' => env('SLACK_WEBHOOK_CV'), // fallback directo a Slack si no hay n8n
        'whatsapp_webhook' => env('OPENCLAW_WHATSAPP_WEBHOOK'), // endpoint OpenClaw para WhatsApp
        'admin_whatsapp' => env('ADMIN_WHATSAPP'), // número de Anderson
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'admin' => [
        // Comma-separated emails allowed into Filament (via Google OAuth or direct login).
        // Must be in config (not env()) to survive config:cache in production.
        'allowed_emails' => array_filter(array_map(
            'trim',
            explode(',', env('ADMIN_ALLOWED_EMAILS', ''))
        )),
    ],

];