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
            '<any-folder-name-1>' => 'public/img',
            '<any-folder-name-2>' => 'public/attachments',            
            '<any-key-name-1>' => '<any-key-value-1>',
            'image_server' => 'http://localhost:port/',
            //path below will be used with image_server so public 
            //is not needed here. This image should be accessible over url
            //e.g http://localhost:port/img/customName1/test.jpg
            '<any-folder-name-3>' => 'img/customeName1/', 
            '<any-folder-name-4>' => 'img/customeName2/'
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


