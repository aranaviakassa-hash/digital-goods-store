<?php

return [

    'admin' => [
        'require_verified_email' => true,
        'require_2fa' => env('ADMIN_REQUIRE_2FA', false),
    ],

    'fraud' => [
        'high_value_order_amount' => 100,
        'new_customer_manual_review' => true,
        'rapid_order_window_minutes' => 10,
        'rapid_order_limit' => 3,
    ],

    'logging' => [
        'mask_sensitive_fields' => true,
    ],

];