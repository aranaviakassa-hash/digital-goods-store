<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Public brand
    |--------------------------------------------------------------------------
    */

    'brand_name' => 'NEXORA',

    'brand_full_name' => 'NEXORA DIGITAL STORE',

    /*
    |--------------------------------------------------------------------------
    | Legal seller identity
    |--------------------------------------------------------------------------
    */

    'legal_name' => '"NEXORA DİGİTAL STORE" Məhdud Məsuliyyətli Cəmiyyəti',

    'legal_short_name' => '"NEXORA DİGİTAL STORE" MMC',

    'tax_id' => '5500772531',

    'activity' => 'Rəqəmsal məhsulların və oyun top-up xidmətlərinin onlayn satışı',

    'legal_address' => 'İmişli rayonu, H. Əliyev pr. 108',

    'business_address' => 'İmişli rayonu, H. Əliyev pr. 108',

    /*
    |--------------------------------------------------------------------------
    | Public contact
    |--------------------------------------------------------------------------
    */

    'phone' => '+994504666744',

    'phone_display' => '+994 50 466 67 44',

    'whatsapp' => '+994504666744',

    /*
     * Domain alınandan sonra yalnız aşağıdakı iki email dəyişəcək.
     * Hələlik public saytda şəxsi Gmail göstərməyəcəyik.
     */
    'email' => null,

    'support_email' => null,

    'support_hours' => '09:00–18:00',

    /*
    |--------------------------------------------------------------------------
    | Commerce
    |--------------------------------------------------------------------------
    */

    'primary_currency' => 'AZN',

    'display_currencies' => [
        'AZN',
        'USD',
    ],

    'checkout_currencies' => [
        'AZN',
    ],

    /*
    |--------------------------------------------------------------------------
    | Delivery
    |--------------------------------------------------------------------------
    */

    'delivery' => [
        'usual_min_minutes' => 1,
        'usual_max_minutes' => 5,
        'maximum_hours' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Refund principles
    |--------------------------------------------------------------------------
    */

    'refund' => [
        'successful_fulfillment_refundable' => false,
        'customer_wrong_identifier_refundable_after_fulfillment' => false,
        'confirmed_supplier_failure_full_refund' => true,
        'duplicate_charge_extra_payment_full_refund' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Customer access
    |--------------------------------------------------------------------------
    */

    'guest_checkout_enabled' => true,

    'google_login_enabled' => true,

    /*
     * Apple login bank review üçün blocking deyil.
     * Provider config tamamlanana qədər false qalır.
     */
    'apple_login_enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Merchant review
    |--------------------------------------------------------------------------
    */

    'merchant_review_banks' => [
        'ABB',
        'PAŞA Bank',
    ],

    /*
     * Real acquiring credentials və provider approval olmadan
     * production payment ready kimi göstərilmir.
     */
    'live_payment_enabled' => false,

];