<?php

namespace Cac\Provider;

use Cac\Interfaces\Provider;
use Cac\Support\Cache;

abstract class ServiceProvider implements Provider
{
    protected function loadViewsFrom($path, $namespace)
    {
       return Cache::set($namespace,$path);
    }
}
