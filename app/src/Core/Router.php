<?php

namespace Core;

/**
 * Class Router
 * @package Core
 * @author Pavel Spichak
 */
class Router
{

    private $uri;
    private $controller;
    private $action;
    private $params;
    private $routeRoles;


    function __construct(string $uri)
    {
        $this->uri = urldecode(trim($uri, '/'));

        $routes = Config::get('routes');
        $mainRouteName = '';
        $routePaths = [];
        foreach ($routes as $routeName => $route) {
            if ($route['path'] == '/') {
                $mainRouteName = $routeName;
            }
            $routePaths[$routeName] = str_replace(['/', '{slug}'], ['\/', '([0-9a-zA-Z]*)'], $route['path']);
        }


        $this->controller = (!empty($mainRouteName) && isset($routes[$mainRouteName]['controller']))
            ? $routes[$mainRouteName]['controller'] : Config::get('default_controller');

        $this->action = (!empty($mainRouteName) && isset($routes[$mainRouteName]['action']))
            ? $routes[$mainRouteName]['action'] : Config::get('default_action');

        $this->routeRoles = (!empty($mainRouteName) && isset($routes[$mainRouteName]['roles']))
            ? $routes[$mainRouteName]['roles'] : [];


        $routeParts = explode('?', $this->uri);
        $currentRoute = '/' . implode('/', $routeParts);
        $routeParts = (!empty($routeParts[0])) ? explode('/', $routeParts[0]) : [];

        if (count($routeParts)) {
            $foundRouteName = '';
            $matches = [];

            foreach ($routePaths as $routeName => $path) {
                if (preg_match('/' . $path . '$/', $currentRoute, $matches)) {
                    $foundRouteName = $routeName;
                    break;
                }
            }
            array_shift($matches);

            if (!$foundRouteName) {
                die('Route not found!');
            }

            $foundedRoute = $routes[$foundRouteName];
            $this->controller = $foundedRoute['controller'];
            $this->action = $foundedRoute['action'];
            $this->routeRoles = isset($foundedRoute['roles']) ? $foundedRoute['roles'] : [];
            $this->params = $matches;
        }
    }


    public function isHaveAccess(array $user): bool
    {
        $userRoles = isset($user['roles']) ? $user['roles'] : [];
        $isHaveAccessToPage = false;
        if (!empty($this->routeRoles)) {
            foreach ($this->routeRoles as $routeRole) {
                if (in_array($routeRole, $userRoles)) {
                    $isHaveAccessToPage = true;
                    break;
                }
            }
        } else {
            $isHaveAccessToPage = true;
        }

        return $isHaveAccessToPage;
    }


    public function getUri(): string
    {
        return $this->uri;
    }


    public function getController(): string
    {
        return $this->controller;
    }


    public function getAction(): string
    {
        return $this->action;
    }


    public function getParams(): array
    {
        return (isset($this->params)) ? $this->params : [];
    }


    public function getRoles(): array
    {
        return (isset($this->roles)) ? $this->roles : [];
    }
}