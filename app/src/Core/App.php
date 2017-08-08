<?php

namespace Core;


/**
 * Class App
 * @package Core
 * @author Pavel Spichak
 */
class App
{

    protected static $router;
    protected static $db;
    protected static $user;


    public static function init(string $uri)
    {
        self::$router = new Router($uri);
        self::$db = new DB(Config::get('db_host'), Config::get('db_user'), Config::get('db_password'), Config::get('db_name'));
        self::$user = Session::get('user');

        if (empty(self::$user)) {
            self::$user = [];
        }

        $controllerName = 'App\Controller\\' . ucfirst(self::$router->getController() . 'Controller');
        $controllerMethod = strtolower(self::$router->getAction()) . 'Action';

        $isHaveAccessToPage = self::$router->isHaveAccess(self::$user);
        if (!$isHaveAccessToPage) {
            die('You do not have permission to access this page!');
        }

        if (class_exists($controllerName)) {
            $controllerObject = new $controllerName;
        } else {
            throw new \Exception('Controller ' . $controllerName . ' does not exist');
        }

        if (method_exists($controllerObject, $controllerMethod)) {
            echo call_user_func_array([$controllerObject, $controllerMethod], self::$router->getParams());
        } else {
            throw new \Exception('Method ' . $controllerMethod . '() does not exist in controller' . $controllerName);
        }
    }


    public static function getRouter(): Router
    {
        return self::$router;
    }


    public static function getDatabase(): DB
    {
        return self::$db;
    }


    public static function getUser(): array
    {
        return (isset(self::$user)) ? self::$user : [];
    }

}