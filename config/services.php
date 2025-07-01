<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Üçüncü Parti Servisler (Third Party Services)
    |--------------------------------------------------------------------------
    |
    | Bu dosya, uygulamanızın kullandığı harici servislerin (Stripe, Mailgun,
    | AWS S3, Twitter API vb.) tüm kimlik bilgilerini (credentials)
    | saklamak için merkezi bir yerdir. Bu, tüm API anahtarlarınızı
    | düzenli bir şekilde yönetmenizi sağlar.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
    ],

    'aws' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
    ],
    
    // Soketi, gerçek zamanlı bildirimler için WebSocket sunucusu
    'soketi' => [
        'app_id' => env('SOKETI_APP_ID'),
        'key' => env('SOKETI_APP_KEY'),
        'secret' => env('SOKETI_APP_SECRET'),
        'host' => env('SOKETI_HOST', '127.0.0.1'),
        'port' => env('SOKETI_PORT', 6001),
        'scheme' => 'http',
    ],

];
