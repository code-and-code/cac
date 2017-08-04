<?php
namespace Cac\Support;

use Desarrolla2\Cache\Cache as DesarrollaCache;
use Desarrolla2\Cache\Adapter\File;

class Cache
{
    private static  $cache;

    private static function init()
    {
        if(!config('app.cache.active'))
        {
            return false;
        }
        else {

            $adapter     = new File(config('app.cache.folder'));
            self::$cache = new DesarrollaCache($adapter);
            return true;
        }
    }

    public static function set($key,$value,$time = 0)
    {
        self::init();
        if(is_object($value))
        {
            $value = objectToArray($value);
        }
        return self::$cache->set($key, $value, $time);
    }

    public static function get($key)
    {
        self::init();

        $cache = self::$cache->get($key);
        if(empty($cache))
        {
            return false;
        }
        return arrayToObject($cache);
    }

    public static function delete($key)
    {
        self::init();
        return self::$cache->delete($key);
    }

}

