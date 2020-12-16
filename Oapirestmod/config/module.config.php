<?php

namespace Oapirestmod;

use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
return [
    'controllers' => [
        'factories' => [
        Controller\RestmodController::class => InvokableFactory::class
        ],
    ],
    'router' => [
        'routes' => [
            'oapirestmod' => [
                'type'    => Segment::class,
                'options' => [
                    // Change this to something specific to your module
                    'route'    => '/api/restmod[/:id]',
                    'defaults' => [
                        'controller'    => Controller\RestmodController::class,
                        //'action'        => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'Oapirestmod' => __DIR__ . '/../view',
        ],
    ],
];
