<?php

namespace Cac\Init;

abstract class Bootstrap
{
    private $routes;

    public function __construct()
    {
        date_default_timezone_set(get_config('timezone'));
        
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    abstract protected function initRoutes();

    protected function run($url)
    {
        array_walk($this->routes, function($route) use ($url){

           if($url == $route['route']){

                if(isset($route['auth']) && $route['auth'])
                {
                    if($this->validation() == true)
                    {
                        $class = "App\\Controllers\\".ucfirst($route['controller']);
                        $controller = new $class;
                        $controller->$route['action']();

                    }
                    else
                    {
                        header("Location: /auth/login");
                    }
                }
                else
                {
                    $class = "App\\Controllers\\".ucfirst($route['controller']);
                    $controller = new $class;
                    $controller->$route['action']();
                }

           }
        });

    }

    protected function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    protected function getUrl()
    {
        $url = parse_url($_SERVER['REQUEST_URI']);
        return $url['path' ];

    }

    protected function validation()
    {
        session_start();
        if(isset($_SESSION['auth']))
        {
            return true;
        }
    }
}