<?php

return [
    'oconfig_manager' => [
        'settings' => [
            'image_server' => 'http://localhost:port/',
            'customeName1_image_path' => 'img/customeName1/',
            'customeName2_image_path' => 'img/customeName2/',
        ],
        'api' => [
            'api_key' => '<api-key>',
        ],
        'ojwt' => [
            'jwt_key' => '<token>', //base64_encode(openssl_random_pseudo_bytes(64]],
            'algo' => 'HS512',
            'server' => 'http://localhost:port/',
            'iatOffset' => 10,
            'expOffset' => 3590 //+ above 10 = 3600 => 1 hr
        ],
        'entities' => [
            'path' => 'Application\Entity'
        ]
    ]
];


