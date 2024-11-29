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

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_SECRET_ID'),
        'redirect' => env('GOOGLE_REDIRECT'),
        'one_tap_redirect' => env('GOOGLE_ONE_TAP_URI'),

        'document_ai' => [
            'enabled' => env('GOOGLE_DOCUMENT_AI_TOGGLE', false),
            'credential_path' => env('GOOGLE_DOCUMENT_AI_CREDENTIALS_PATH'),
            'processor_id' => env('GOOGLE_DOCUMENT_AI_PROCESSOR_ID'),
            'processor_ver' => env('GOOGLE_DOCUMENT_AI_PROCESSOR_VER'),
            'processor_loc' => env('GOOGLE_DOCUMENT_AI_PROCESSOR_LOCATION'),
        ],

    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_SECRET_ID'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

];
