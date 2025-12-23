<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Adjust these settings to allow your frontend origin(s) to send
    | credentials (cookies) to this application in production.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'],

    'allowed_methods' => ['*'],

    // Provide a comma-separated list in .env, e.g. CORS_ALLOWED_ORIGINS=https://app.example.com
    'allowed_origins' => array_filter(array_map('trim', explode(',', env('CORS_ALLOWED_ORIGINS', '')))),

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // IMPORTANT: set to true to allow cookies to be sent cross-site
    'supports_credentials' => true,
];
