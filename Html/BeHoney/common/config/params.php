<?php
return [
    'adminEmail' => 'admin@localhost',
    'robotEmail' => 'support@localhost',
    'user.passwordResetTokenExpire' => 3600,
    'files' => [
        'defaultMaxSize' => 10, //megabytes
    ],
    'frontendWeb' => null,
    'backendWeb' => null,
    'checkout' => [
        'transaction_type' => 'payment',
        'version' => 2,
        'settings' => [
            'success_url' => null,
            'decline_url' => null,
            'fail_url' => null,
            "notification_url" => null,
            'language' => 'ru',
        ]
    ],
    'request' => [
        'currency' => 'BYN',
        'notification_url' => null,
        'payment_method' => [
            'type' => 'erip',
            'account_number' => '123',
            'service_no' => '99999999',
        ],
    ],
    'shopId' => null,
    'secretKey' => null,
    'instagram' => [
        'token' => null,
        'userId' => null,
        'limit' => null,
        'size' => null,
    ],
    'tiny' => [
        'key' => null,
    ],
];
