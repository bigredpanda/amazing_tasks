<?php

use Core\Config;

require_once 'routes.php';
require_once 'db.php';

Config::set('site_name', 'amazingTasks');
Config::set('site_url', 'http://localhost');
Config::set('display_errors', 'on');
Config::set('default_controller', 'default');
Config::set('default_action', 'index');
Config::set('image_upload_directory', 'web/upload/');


Config::set('users', [
    'admin' => [
        'password' => '123',
        'roles'    => ['admin']
    ]
]);