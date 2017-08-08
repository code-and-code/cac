<?php

namespace Cac\TwigHelper;

use Cac\Support\Cache;

class TwigFunction extends \Twig_Extension {

    public function getName()
    {
        return 'TwigFunction';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('auth',  [$this, 'getAuth' ]),
            new \Twig_SimpleFunction('guest', [$this, 'getGuest']),
            new \Twig_SimpleFunction('view',  [$this, 'getView' ]),
            new \Twig_SimpleFunction('limit', [$this, 'getLimit']),
            new \Twig_SimpleFunction('hasErrors', [$this, 'getHasErrors']),
            new \Twig_SimpleFunction('hasMessages', [$this, 'getHasMessages']),
            new \Twig_SimpleFunction('display', [$this, 'getDisplay']),
            new \Twig_SimpleFunction('assets', [$this, 'getAssets'])
        ];
    }

    public function getAuth($name = null)
    {
        if($this->getGuest())
        {
            return 'NULL';
        }
        return auth($name);
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

    public function getLimit($string,$start = 0,$end =50)
    {
        $string = substr($string,$start,$end);
        return $string;
    }

    public function getHasErrors()
    {
        return hasErrors();
    }

    public function getHasMessages()
    {
        return hasMessages();
    }

    public function getDisplay()
    {
        return display();
    }

    public function getAssets($src)
    {
        return assets($src);
    }
}
