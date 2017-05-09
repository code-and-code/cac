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

if (! function_exists('config')) {

    function config($name)
    {
        $name    = explode('.', $name);
        $configs = config_exist(current($name));

        unset($name[0]);
        for($i =1;$i <= count($name)-1; $i++)
        {
            $configs = $configs[$name[$i]];
        }
        return $configs[end($name)];
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

if (! function_exists('auth')) {

    function auth()
    {
        @session_start();
        return getAuthUser($_SESSION['auth']['id']);
    }
}

if (! function_exists('guest')) {

    function guest()
    {
        @session_start();

        if(isset($_SESSION['auth']))
        {
            return true;
        }
        return false;
    }
}

if (! function_exists('viewPath')) {

    function viewPath($name = null)
    {
        $extension = config('app.layout.extension');
        
        $base = __DIR__.'../../../../App/views/';
        $view = str_replace('.','/',$name);

        if(is_null($name))
        {
            return $base;
        }
        return $base.$view.$extension;
    }
}

if (! function_exists('arrayToObject')) {

    function arrayToObject($array) {
        if (!is_array($array)) {
            return $array;
        }
        $object = new \stdClass();
        if (is_array($array) && count($array) > 0) {
            foreach ($array as $name=>$value) {
                $name = strtolower(trim($name));
                if (!empty($name)) {
                    $object->$name = arrayToObject($value);
                }
            }
            return $object;
        }
        else {
            return FALSE;
        }
    }
}


if (! function_exists('objectToArray')) {

    function objectToArray($obj) {
        if (!is_object($obj)) {
            return $obj;
        }
        foreach ($obj as $key=>$attribute) {
            $values[$key] = $attribute;
        }
        return $values;
    }
}


if (! function_exists('getAuthUser')) {

    function getAuthUser($id) {

        $class = config('auth.auth.class');
        $user  = new $class;
        return $user->find($id);
    }
}


