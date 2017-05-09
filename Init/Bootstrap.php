<?php

namespace Cac\Init;

use Cac\Controller\Action;

abstract class Bootstrap
{
    private $routes;
    private $action;
    private $not;

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

            if($url->path == $route->route){
               if(isset($route->method))
               {
                   if($url->method!= $route->method)
                   {
                      $msg = "Rota <b>{$url->path}</b> Precisa precisa ser acessada pelo REQUEST_METHOD = <b>{$route->method}</b>";
                      return $this->notFound($msg);
                   }
               }

               if(isset($route->auth) && $route->auth)
                {
                    if($this->validation() == true)
                    {
                        $this->runMethod($route->controller,$route->action);
                    }
                    else
                    {
                        $config = config('auth.auth');
                        header("Location:".$config['notauthorized']);
                    }
                    $this->not--;
                }
                else
                {
                    $this->runMethod($route->controller,$route->action);

                }
            $this->not--;
           }
           else
            {
                $this->not++;
            }
        });

        if(count((array)$this->routes) ===$this->not++)
        {
            $msg = "URL <b>{$url->path}</b> Not Found <b>404</b>";
            $this->notFound($msg);
        }
    }

    protected function setRoutes($routes)
    {
        $this->routes = arrayToObject($routes);
    }

    protected function getUrl()
    {
        $url             = parse_url($_SERVER['REQUEST_URI']);
        $url['method']   = $_SERVER['REQUEST_METHOD'];
        $url['notfound'] = true;

        return arrayToObject($url);
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
         echo $msg;
    }

   protected function runMethod($class,$method)
   {
      if (!method_exists($this->getClass($class),$method)){

          echo 'Method not found';
      }
      call_user_func_array([$this->getClass($class),$method],[]);
  }

  protected function getClass($name)
  {
     $class = "App\\Controllers\\".ucfirst($name);
     return new $class;
  }
}
