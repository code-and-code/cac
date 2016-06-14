<?php

namespace Cac\Controller;


class Action{

    protected $view;
    protected $action;
    protected $srcView;
    protected $layout;

    public function __construct()
    {
        $this->view = new \stdClass();
        $this->validPublic();
        $this->setLayout(get_config('layout')['default']);
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function render($action,array $vars = null, $layout = true)
    {
        $this->action = $action;
        $this->addVars($vars);

        if($layout == true && file_exists($this->srcView.$this->layout)){

            include_once($this->srcView.$this->layout);

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
        $action = str_replace(".","/",$this->action);
        include_once($this->srcView.$action.'.phtml');
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
    
    public function validator_null(array $attributes, array $rules)
    {
       if(!empty($attributes) && !empty($rules)){

           foreach ($attributes as $key=>$attribute)
           {
               if(in_array($key,$rules))
               {
                   if(empty($attribute) || is_null($attribute))
                   {
                       return false;
                   }
               }
           }
              return true;
        }
        else
        {
            return false;
        }
    }
}
