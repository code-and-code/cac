<?php
namespace Cac\Controller;

use Cac\Support\Cache;
use Cac\TwigHelper\TwigFunction;

class Action {

    private $folder;
    private $twig;
    private $vars;

    private function setFolder($folder)
    {
        $this->folder = $folder;
        $this->twig   = new \Twig_Loader_Filesystem($this->folder);
    }

    private function twigAddGlobalVars()
    {
        if(!empty($this->vars))
        {
            foreach ($this->vars as $key=>$var)
            {
                $this->twig->addGlobal($key, $var);
            }
        }
    }

    private function twigAddExtension()
    {
        $this->twig->addExtension(new TwigFunction());
    }

    private function init($reload)
    {
        $this->twig   = new \Twig_Environment($this->twig,
            ['cache'       => config('app.layout.cache'),
             'auto_reload' => $reload]);

        $this->twigAddExtension();
        $this->twigAddGlobalVars();
    }

    protected function setVars($var,$value)
    {
        $this->vars[$var] = $value;
        return $this;
    }

    public function render($action,array $vars = [],$reload = true)
    {
        if(strpos($action, ':'))
        {
            $array = explode(':',$action);
            $action = end($array);
            $this->setFolder(Cache::get(prev($array)));
        }
        else
        {
            $this->setFolder(config('app.layout.folder'));
        }
        $this->init($reload);
        $action = str_replace(".","/",$action);
        return  $this->twig->render($action.config('app.layout.extension'), $vars);
    }
}
