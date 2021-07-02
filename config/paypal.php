<?php

return [
    'environment' => 'sandbox',

    'channel'     =>  env('PAYPAL_CHANNEL','paypal'),

    'sandbox'     => [
        'client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID',''),
        'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET',''),
        'return_url'    => env('PAYPAL_SANDBOX_RETURN_URL',''),
        'cancel_url'    => env('PAYPAL_SANDBOX_CANCEL_URL',''),
    ],

    'production' => [
        'client_id'     => env('PAYPAL_PRODUCTION_CLIENT_ID',''),
        'client_secret' => env('PAYPAL_PRODUCTION_CLIENT_SECRET',''),
        'return_url'    => env('PAYPAL_PRODUCTION_RETURN_URL',''),
        'cancel_url'    => env('PAYPAL_PRODUCTION_CANCEL_URL',''),
    ]
];
