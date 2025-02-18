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
    'stripe' => [
        'key' => env('STRIPE_KEY', 'pk_test_51QDmsbP9tHF5rYiLanPl7vItLhUadZD73dDJJMYMmfRoFnfq1SiRnADgI9fzUbkzQjGBniuFXwWhNo9tgEtE2U9000yjXolUsJ'),

        'secret' => env('STRIPE_SECRET', 'sk_test_51QDmsbP9tHF5rYiLJN7zSjJRQIKk0gEkwReIuDElwlIrY6mY4LJiWCOAyrdDdTE3lxgkwnuN9Rbq65z1gny9Jfy500QgpBIYa0'),

        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET', 'whsec_824f7be82f73b6ec7b10927bae5e1810bf4e0d8b9d121b73f62a921f9313b087'),
    ],
];
