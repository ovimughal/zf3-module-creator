<?php

namespace ZendSkeletonModule;

use Laminas\Router\Http\Method;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            Controller\SkeletonController::class => InvokableFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'module-name-here' => [
                'type' => 'literal',
                'options' => [
                    // Change this to something specific to your module
                    'route' => '/module-specific-route',
                ],
                'may_terminate' => false,
                'child_routes' => [
                    // You can place additional routes that match under the
                    // route defined above here.
                    'get' => [
                        'type' => Method::class,
                        'options' => [
                            // Change this to something specific to your module
                            'verb' => 'get',
                            'defaults' => [
                                'controller' => Controller\SkeletonController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'post' => [
                        'type' => Method::class,
                        'options' => [
                            // Change this to something specific to your module
                            'verb' => 'post',
                            'defaults' => [
                                'controller' => Controller\SkeletonController::class,
                                'action' => 'foo',
                            ],
                        ],
                    ],
                    'put' => [
                        'type' => Method::class,
                        'options' => [
                            // Change this to something specific to your module
                            'verb' => 'put',
                            'defaults' => [
                                'controller' => Controller\SkeletonController::class,
                                'action' => 'bar',
                            ],
                        ],
                    ],
                    'delete' => [
                        'type' => Method::class,
                        'options' => [
                            // Change this to something specific to your module
                            'verb' => 'delete',
                            'defaults' => [
                                'controller' => Controller\SkeletonController::class,
                                'action' => 'exceptionExample',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'ZendSkeletonModule' => __DIR__ . '/../view',
        ],
    ],
];
