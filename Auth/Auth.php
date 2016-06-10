<?php

namespace Cac\Auth;

use Cac\Controller\Action;

class Auth extends Action
{

    public function getLogin()
    {
        echo $this->render($this->viewLogin);
    }

    public function postLogin()
    {
        $request = $_REQUEST;
        return $this->auth($request);
    }

    public function getRegister()
    {
        echo $this->render($this->viewRegister);
    }

    public function postRegister()
    {
        $request = $_REQUEST;
        $this->store($request);
    }

    public function logout()
    {
        @session_start();
        unset($_SESSION['auth']);
    }

    public function auth(array $array)
    {
        $class  = new $this->class();

        $result = $class->where('email','=',$array['email'])->andWhere('password','=',$array['password'])->first();

        if($result)
        {
            $this->sessionStar($result);
            echo  'Login OK'.auth('email');
        }
        else
        {
            echo 'Auth Fail';
        }

    }

    private function sessionStar($object)
    {
        session_start();
        $_SESSION['auth'] = $object->toArray();
    }


}