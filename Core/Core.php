<?php

namespace Cac\Core;

use Cac\Exception\ProviderException;
use Cac\Provider\ServiceProvider;
use Cac\Route\Router;

class  Core
{
    public static function on($providers)
    {
        self::register($providers);
        Router::execute();
    }

    protected static function register($providers)
    {
        foreach ($providers as $provider)
        {
            $class = new $provider();

            if( $class instanceof ServiceProvider)
            {
                call_user_func_array([new $provider(),'boot'],[]);
            }
            else
            {
                throw new ProviderException("Provider inválida : ".get_class($class));
            }
        }
    }
}
