<?php

namespace Cac\Auth;

interface AuthInterface
{
    public function viewLogin();
    public function store(array $user);
}