<?php
return [
    'api_key'        => env('PAYMOB_API_KEY'),
    'integration_id' => env('PAYMOB_INTEGRATION_ID'),
    'iframe_id'      => env('PAYMOB_IFRAME_ID'),
    'auth_url'       => env('PAYMOB_AUTH_URL'),
    'order_url'      => env('PAYMOB_ORDER_URL'),
    'payment_key_url'=> env('PAYMOB_PAYMENT_KEY_URL'),
    'iframe_url'     => env('PAYMOB_IFRAME'),
    'hmac_key'       =>env('PAYMOB_HMAC_KEY'),
];
