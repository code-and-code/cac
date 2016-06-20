<?php

namespace Cac\Init;

use Cac\Controller\Action;

abstract class Bootstrap
{
    private $routes;
    private $action;

    public function __construct()
    {
        date_default_timezone_set(config('app.timezone'));
        $this->action = new Action();

        
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    abstract protected function initRoutes();

    protected function run($url)
    {
        array_walk($this->routes, function($route) use ($url){

            if($url['path'] == $route['route']){

               if(isset($route['method']))
               {
                   if($url['method']!= $route['method'])
                   {
                      $msg = "Rota <b>{$url['path']}</b> Precisa precisa ser acessada pelo REQUEST_METHOD = <b>{$route['method']}</b>";
                      return $this->notFound($msg);
                   }
               }

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
                        $config = config('auth.auth');
                        header("Location:".$config['notauthorized']);

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
        $url             = parse_url($_SERVER['REQUEST_URI']);
        $url['method']   = $_SERVER['REQUEST_METHOD'];
        $url['notfound'] = true;
        return $url;
    }

    protected function validation()
    {
        @session_start();
        if(isset($_SESSION['auth'])){
            return true;
        }
    }

    protected function notFound($msg)
    {
        echo $this->action->maker('errors/routenotfound.phtml', ['msg' => $msg],true);
    }
}