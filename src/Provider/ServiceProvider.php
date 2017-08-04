<?php

namespace Cac\Provider;

use Cac\Interfaces\Provider;
use Cac\Support\Cache;

abstract class ServiceProvider implements Provider
{
    private function loadViewsFrom($path, $namespace)
    {
       return Cache::set($namespace,$path);
    }
}
