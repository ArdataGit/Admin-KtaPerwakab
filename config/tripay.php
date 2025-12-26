<?php

$isDev = env('TRIPAY_DEV', true);

return [

    'dev' => $isDev,

    // Credential
    'merchant_code' => $isDev
        ? env('TRIPAY_SANDBOX_MERCHANT_CODE')
        : env('TRIPAY_PROD_MERCHANT_CODE'),

    'api_key' => $isDev
        ? env('TRIPAY_SANDBOX_API_KEY')
        : env('TRIPAY_PROD_API_KEY'),

    'private_key' => $isDev
        ? env('TRIPAY_SANDBOX_PRIVATE_KEY')
        : env('TRIPAY_PROD_PRIVATE_KEY'),

    // Endpoint
    'base_url' => $isDev
        ? 'https://tripay.co.id/api-sandbox'
        : 'https://tripay.co.id/api',

    // URLs
    'callback_url' => $isDev
        ? env('TRIPAY_SANDBOX_CALLBACK_URL')
        : env('TRIPAY_PROD_CALLBACK_URL'),

    'return_url' => $isDev
        ? env('TRIPAY_SANDBOX_RETURN_URL')
        : env('TRIPAY_PROD_RETURN_URL'),
];
