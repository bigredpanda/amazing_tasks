<?php

use Core\Config;

Config::set('routes', [
    'index_page' => [
        'path'       => '/',
        'controller' => 'task',
        'action'     => 'index'
    ],

    'create_task' => [
        'path'       => '/task/create',
        'controller' => 'task',
        'action'     => 'create'
    ],

    'update_task' => [
        'path'       => '/task/edit/{slug}',
        'controller' => 'task',
        'action'     => 'update',
        'roles'      => ['admin']
    ],

    'remove_task' => [
        'path'       => '/task/remove/{slug}',
        'controller' => 'task',
        'action'     => 'remove',
        'roles'      => ['admin']
    ],

    'login' => [
        'path'       => '/login',
        'controller' => 'auth',
        'action'     => 'login',
    ],

    'logout' => [
        'path'       => '/logout',
        'controller' => 'auth',
        'action'     => 'logout',
    ]
]);