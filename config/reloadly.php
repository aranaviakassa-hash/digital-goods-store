<?php

return [
    'enabled' => env('RELOADLY_ENABLED', false),

    'base_url' => env('RELOADLY_BASE_URL'),

    'client_id' => env('RELOADLY_CLIENT_ID'),

    'client_secret' => env('RELOADLY_CLIENT_SECRET'),

    'audience' => env('RELOADLY_AUDIENCE'),

    'timeout' => env('RELOADLY_TIMEOUT', 15),
];