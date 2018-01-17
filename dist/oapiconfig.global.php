<?php
/*
 * Replace names enclosed in <> like <customName1> with any name you want
 * without <>. All folder paths like 'img/', 'img/customeName1',
 * 'Application\Entity' are imaginary you can keep them or use your own.
 * You can cahnge offset to any you want.
 */
return [
    'oconfig_manager' => [
        'settings' => [
            'enable_login' => false,
            'enable_db_acl' => false,
            'app_development_env' => getenv('APPLICATION_ENV') == 'production' ? false : true,//or use true/false
            '<any name or empty>_file_path' => 'img/',
            '<customeName1>_file_path' => '',
            'image_server' => 'http://localhost:port/',
            '<customeName1>_image_path' => 'img/customeName1/',
            '<customeName2>_image_path' => 'img/customeName2/',
        ],
        'api' => [
            'api_key' => '<api-key>',//base64_encode(openssl_random_pseudo_bytes(64))
        ],
        'ojwt' => [
            'jwt_key' => '<token>', //base64_encode(openssl_random_pseudo_bytes(64))
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


