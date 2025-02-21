<?php

namespace Persona;

use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Persona\Model\PersonaFactory;
use Laminas\Router\Http\Segment;

return [
    'controllers' => [
        'factories' => [
            Controller\PersonaController::class => ReflectionBasedAbstractFactory::class
        ],
    ],
    'router' => [
        'routes' => [
            'persona' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/persona[/:action[/:idPersona]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'idPersona' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\PersonaController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'persona' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy'
        ],
    ],
    'service_manager' => [
        'factories' => [
            Persona\Model\PersonaTable::class => Persona\Model\PersonaTableFactory::class,
        ],
    ],
];
