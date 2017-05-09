<?php

namespace Cac\Auth;

use Cac\Controller\Action;

class Auth extends Action
{
    private $config;

    public function setConfig($name)
    {
        $this->config = config($name);
    }

    public function getLogin()
    {
        echo $this->render($this->config['viewLogin']);
    }

    public function postLogin()
    {
        $request = $_REQUEST;
        $request['password'] = md5($request['password']);
        return $this->auth($request);
    }

    public function getRegister()
    {
        echo $this->render($this->config['viewRegister']);
    }

    public function postRegister()
    {
        $request = $_REQUEST;
        $request['password'] = md5($request['password']);
        $this->store($request);
    }

    public function logout()
    {
        @session_start();
        unset($_SESSION['auth']);
        header("Location:".$this->config['notauthorized']);
    }

    public function auth(array $array)
    {
        $class  = new $this->config['class']();
        $result = $class->where('email','=',$array['email'])->andWhere('password','=', $array['password'])->first();

        if($result)
        {
            $this->sessionStar($result);
            header("Location:".$this->config['redirect']);
        }
        else
        {
            echo 'Auth Fail';
        }
    }

    private function store($array)
    {
        $class  = new $this->config['class']();
        $result = $class->create($array);
        $this->auth($result->toArray());
    }

    private function sessionStar($object)
    {
        session_start();
        $_SESSION['auth'] = $object->toArray();
    }
}
