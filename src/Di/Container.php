<?php
namespace Cac\Di;

class Container
{
    public static function getClass($name)
    {
        $strClass = '\\App\\Models\\'.ucfirst($name);
        $class    = new  $strClass(\Cac\Init\Bootstrap::getDb());
        return $class;
    }
}