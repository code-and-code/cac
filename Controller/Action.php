<?php

namespace Cac\Controller;


class Action{

    protected $view;
    protected $action;
    protected $srcView;

    public function __construct()
    {
        $this->view = new \stdClass();
        $this->validPublic();
    }

    public function render($action,array $vars = null, $layout = true)
    {
        $this->action = $action;
        $this->addVars($vars);

        $default =  get_config('layout')['default'];

        if($layout == true && file_exists($this->srcView.$default)){

            include_once($this->srcView.$default);

        }else
        {
            $this->content();
        }
    }

    public function maker($view, $vars = null,$html = false)
    {
        if(file_exists($this->srcView.$view)){

            if($html == true)
            {
                $view = file_get_contents($this->srcView.$view);
                return $this->replaceTags($vars,$view);
            }else
            {
                $this->addVars($vars);
                return include_once($this->srcView.$view);
            }
        }
        else
        {
            throw  new \Exception ('View not found');
        }
    }

    public function content()
    {
        $class = get_class($this);
        $singleClassName = strtolower(str_replace('App\\Controllers\\','',$class));
        include_once($this->srcView.$singleClassName.'/'.$this->action.'.phtml');
    }

    private function validPublic()
    {
        $public =  get_config('public');
        $folder =  get_config('layout')['folder'];

        ($public == true) ? $this->srcView = "../App/views/{$folder}" : $this->srcView = "App/views/{$folder}";
    }

    private function addVars($vars)
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
        $tag = get_config('layout')['tag'];

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
