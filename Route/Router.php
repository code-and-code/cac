<?php

namespace Cac\Route;


class Router {

    public static $routes  = array();
    public static $layouts = array();

    public static function route($pattern) {

        $change = str_replace('$var', '\d+', $pattern['route']);
        $key    = '/^' . str_replace('/', '\/', $change) . '$/';
        self::$routes[$key] = arrayToObject($pattern);
    }

    public static function get($pattern)
    {
        $pattern['method'] = 'GET';
        self::route($pattern);
    }

    public static function post($pattern)
    {
        $pattern['method'] = 'POST';
        self::route($pattern);
    }

    public static function execute() {

        $url = self::url();

        foreach (self::$routes as $pattern => $config) {

            if (preg_match($pattern, $url->path, $params)) {

                array_shift($params);

                if(isset($config->method))
                {
                    if($url->method!= $config->method)
                    {
                        return self::notFound("Metodo <b>{$url->path}</b> nao e permitido por essa requicao HTTP. <b>Error 401</b>");
                    }
                }

                if(isset($config->auth) && $config->auth)
                {
                    if(self::validation() != true)
                    {
                        self::runMethod($config->namespace,$config->controller,$config->action,$params);
                    }
                    else
                    {
                        $config = config('auth.auth');
                        redirect($config['notauthorized']);
                    }
                }
                else
                {
                    return self::runMethod($config->namespace,$config->controller,$config->action,$params);

                }
            }else
            {
                $url->notfound = true;
            }
        }

        if($url->notfound)  self::notFound("Pagina <b>{$url->path}</b> nao encontrada. <b> Error 404 </b>");
    }

    protected static function url()
    {
        $url             = parse_url($_SERVER['REQUEST_URI']);
        $url['method']   = $_SERVER['REQUEST_METHOD'];
        $url['notfound'] = false;

        return arrayToObject($url);
    }

    protected static function validation()
    {
        @session_start();
        if(isset($_SESSION['auth'])){
            return true;
        }
    }

    protected static function notFound($msg)
    {
        echo $msg;
    }

    protected static function runMethod($name,$class,$method,$params)
    {
        if (!method_exists(self::getClass($name,$class),$method)){

            self::notFound('Method not found');
        }
        else
        {
            return call_user_func_array([self::getClass($name,$class),$method],$params);
        }
    }

    protected static function getClass($name,$class)
    {
        $class = $name.ucfirst($class);
        return new $class;
    }
}
