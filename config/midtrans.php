<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | Server Key: Used for backend API calls (charge, refund, etc.)
    | Client Key: Used for frontend Snap.js popup
    | isProduction: false = sandbox, true = production
    |
    */

    'serverKey' => env('MIDTRANS_SERVER_KEY'),
    'clientKey' => env('MIDTRANS_CLIENT_KEY'),
    'merchantId' => env('MIDTRANS_MERCHANT_ID'),
    'isProduction' => env('MIDTRANS_IS_PRODUCTION', false),
    'isSanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is3ds' => env('MIDTRANS_IS_3DS', true),
];