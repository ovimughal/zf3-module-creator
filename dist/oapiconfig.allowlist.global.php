<?php

return [
    'oconfig_manager' => [
        'allowList' => [
            [
                'role' => ['Role_1'],
                'module' => 'ModuleName_1',
                'controller' => 'ControllerName_1',
                'route' => [
                    'Route_1' => ['GET', 'PUT', 'DELETE'],
                    'Route_2' => ['GET', 'POST', 'PUT', 'DELETE']
                ],
            ],
            [
                'role' => ['Role_1', 'Role_2'],
                'module' => 'ModuleName_2',
                'controller' => 'ControllerName_2',
                'route' => [
                    'Route_3' => ['GET', 'POST']
                ]
            ]
        ]
    ]
];


