<?php

namespace Cac\TwigHelper;

class TwigFunction extends \Twig_Extension {

    public function getName()
    {
        return 'TwigFunction';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('auth',  [$this, 'getAuth']),
            new \Twig_SimpleFunction('guest', [$this, 'getGuest']),
            new \Twig_SimpleFunction('view',  [$this, 'getView'])
        ];
    }

    public function getAuth($name = null)
    {
         if($this->getGuest())
         {
             return auth($name);
         }
         return 'NULL';
    }

    public function getGuest()
    {
        return guest();
    }

    public function getView($name)
    {
        $name = str_replace('.','/',$name);
        return $name.config('app.layout.extension');
    }
}
