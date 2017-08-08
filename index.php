<?php

use Core\App;
use Core\Config;
use Core\Session;

require_once 'vendor/autoload.php';
require_once 'app/config/settings.php';

define('ROOT', dirname(__FILE__));
ini_set('display_errors', Config::get('display_errors'));

Session::init();
App::init($_SERVER['REQUEST_URI']);