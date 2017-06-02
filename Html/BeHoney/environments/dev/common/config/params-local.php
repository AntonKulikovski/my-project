<?php
return [
    'frontendWeb' => null,  // e.g '//behoney.vg',
    'checkout' => [
        'settings' => [
            'success_url' =>  null,  // e.g 'http://behoney.vg/payment/card/',
            'decline_url' =>  null,  // e.g 'http://behoney.vg/payment/decline/',
            'fail_url' =>  null,  // e.g 'http://behoney.vg/payment/fail/',
            "notification_url" =>  null,  // e.g "http://behoney.vg/payment/notification-card/",
        ]
    ],
    'shopId' =>  null,  // e.g '955',
    'secretKey' =>  null,  // e.g 'baba70926d5b106f3eaf47245964e5f051a15e65b3839964e7e8ea964452d26c',
    'request' => [
        'currency' => null,  // e.g 'BYN',
        'notification_url' => null,  // e.g "http://behoney.vg/payment/notification/",
    ],
    'instagram' => [
        'token' => null, // e.g '4104758654.cfe1576.8ed59c63b95n4e498a5a259cd34a9d31',
        'userId' => null, // e.g '4104758654' or self,
        'limit' => null, // e.g 4,
        'size' => null, // e.g 220,
    ],
    'tiny' => [
        'key' => null, // e.g. 'oWpan4zlGSI_bh3B1od3JKSehVt1uNon',
    ],
];
