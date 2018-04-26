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
            // Start-Custom Keys
            '<any-folder-name-1>' => 'public/img',
            '<any-folder-name-2>' => 'public/attachments',            
            '<any-key-name-1>' => '<any-key-value-1>',
            // End-Custom Keys
            
            // Start-For Reporting Engine
            // For Java Bridge you need Tomcat Server & deploy JavaBridge 
            // And you also need to configure Jasper Reporting to Tomcat
            'java_bridge' => 'http://localhost:8090/JavaBridge/java/Java.inc', // default
            'dbms' => 'sqlsrv',//or mysql
            'dbms_server' => '<ip-address>:<port>', //eg: 190.180.170.160:9000
            'data_base_name' => '<dataBaseName>', //eg: MyTestDataBase
            'data_base_user' => '<user>', // eg: root or sa
            'data_base_password' => '<password>',
            'reporting_templates' => '<folder-path>',//eg: public/reporting/templates
            'reporting_output' => '<folder-path>',//eg: 'public/reporting/output'
            'output_file_name' => '<file-name-without-ext>', // eg: output.pdf, output.txt, output.csv without extension
            'output_file_download_route' => '<url-route>',// eg: http://localhost:9005/download,
            // End-For Reporting Engine
            
            // Start-For File Data Engine
            'file_server' => 'http://localhost:8083/',
            // path below will be used with file_server so public 
            // is not needed here. This image/file should be accessible over url
            // e.g http://localhost:port/img/customName1/test.jpg
            // Start-custom keys for Image Data
            '<any-folder-name-3>' => 'img/customeName1/', 
            '<any-folder-name-4>' => 'img/customeName2/',
            // End-custom keys for Image Data
            // End-For File Data Engine
        ],
        'api' => [
            'api_key' => '<api-key>',//base64_encode(openssl_random_pseudo_bytes(64))
            'hyperlink_api_key_security_one' => '12sbt', // Use any 5 alphanumeric
            'hyperlink_api_key_security_two' => 'nJ65s', // use any 5 alphanumeric
            'hyperlink_security_salt' => 'hjgtds', // use any alphanumeric upto n number
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


