<?php

namespace Cac\Provider;

interface ServiceProvider
{

    public function boot();
    public function mapRoutes();
}
