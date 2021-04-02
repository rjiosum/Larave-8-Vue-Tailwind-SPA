<?php

return [
    'url' => env('PASSPORT_OAUTH_TOKEN_URL', '/oauth/token'),

    'cookie' => [
        'name' => env('PASSPORT_COOKIE_NAME', '_token'),
        'minutes' => env('PASSPORT_COOKIE_MINUTES', 0),
        'path' => env('PASSPORT_COOKIE_PATH', null),
        'domain' => env('PASSPORT_COOKIE_DOMAIN', null),
        'secure' => env('PASSPORT_COOKIE_SECURE', null), //null for localhost, true for production
        'httponly' => env('PASSPORT_COOKIE_HTTPONLY', true),
        'raw' => env('PASSPORT_COOKIE_RAW', false),
        'samesite' => env('PASSPORT_COOKIE_SAMESITE', 'strict') //strict or lxs
    ]
];
