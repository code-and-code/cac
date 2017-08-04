<?php

namespace Cac\Provider;

use Cac\Interfaces\Provider;
use Cac\Support\Cache;

abstract class ServiceProvider implements Provider
{
    protected function loadViewsFrom($namespace,$path)
    {
        Cache::set($namespace,$path);
    }
}
