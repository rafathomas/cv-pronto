<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Gateway de pagamento padrão
    |--------------------------------------------------------------------------
    |
    | Resolve a implementação de PaymentGatewayInterface. O domínio de
    | Subscription/Payment nunca deve depender diretamente do SDK do gateway.
    |
    */

    'gateway' => env('PAYMENT_GATEWAY', 'mercadopago'),

    'gateways' => [
        'mercadopago' => [
            'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),
            'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),
            'webhook_secret' => env('MERCADOPAGO_WEBHOOK_SECRET'),
        ],
    ],
];
