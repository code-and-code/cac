<?php

namespace Cac\Controller;


class Action{

    protected $view;
    protected $action;
    protected $srcView;

    private $folder;
    private $twig;

    public function __construct()
    {
        $this->view = new \stdClass();
        $this->setFolder(config('app.layout.folder'));
    }

    private function setFolder($folder)
    {
        $this->folder = '../'.$folder;
        $loader       = new \Twig_Loader_Filesystem($this->folder);
        $this->twig   = new \Twig_Environment($loader, array());
    }

    public function render($action,array $vars = null)
    {
        $action = str_replace(".","/",$action);
        return    $this->twig->render($action.config('app.layout.extension'), $vars);
    }

    public function maker($view, $vars = null,$html = false)
    {
        $src = $this->validSrc();

        if(file_exists($src.$view)){

            if($html == true)
            {
                $view = file_get_contents($src.$view);
                return $this->replaceTags($vars,$view);
            }else
            {
                $this->addVars($vars);
                return include_once($src.$view);
            }
        }
        else
        {
            throw  new \Exception ('View not found');
        }
    }

   private function validSrc()
   {
       $public =  config('app.public');
       $folder =  config('app.layout.folder');
       ($public == true) ? $src = "../{$folder}" : $src = $folder;
       return $src;
   }

    public function addVars($vars)
    {
        if(is_array($vars))
        {
            foreach ($vars as $key => $var)
            {
                $this->view->$key = $var;
            }
        }
    }

    private function replaceTags($vars,$file)
    {
        $tag = config('app.layout.tag');

        if(is_array($vars))
        {
            foreach ($vars as $key => $var)
            {
                $file = str_replace("{$tag[0]}".$key."{$tag[1]}",$var,$file);
            }
        }
        return $file;
    }
}
