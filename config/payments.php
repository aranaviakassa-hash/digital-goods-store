<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Webhook Secret
    |--------------------------------------------------------------------------
    |
    | Local/internal webhook verification üçün istifadə olunur.
    | ABB və ya PAŞA Bank inteqrasiyası gələndə bunu onların real
    | signature verification mexanizmi ilə əvəz edəcəyik.
    |
    */

    'webhook_secret' => env('PAYMENT_WEBHOOK_SECRET'),
];