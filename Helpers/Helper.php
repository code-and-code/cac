<?php

if (! function_exists('get_config')) {

    function get_config($name = null,$config = 'app')
    {
        $configs = config_exist($config);
        if($name ==  null && empty($name))
        {
            return $configs;
        }
        elseif (array_key_exists($name,$configs))
        {
            return $configs[$name];
        }
        else{

            return 'Config not found';
        }
    }
}

if (! function_exists('config_exist')) {

    function config_exist($config)
    {
        $name = $config.".php";

        if(!file_exists(__DIR__."/../../../../App/config/{$name}"))
        {
            throw  new Exception("File {$name} not found in Path App/config");
        }
        else
        {
            $config = include(__DIR__."/../../../../App/config/{$name}");
            return $config;
        }
    }
}











