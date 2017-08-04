<?php

namespace Cac\Interfaces;

interface Provider
{
    public function boot();
    public function mapRoutes();
}
