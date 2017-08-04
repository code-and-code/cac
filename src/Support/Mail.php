<?php

namespace Cac\Support;

use PHPMailer;

class Mail
{
    private $mail;
    private $configs;

    public function __construct(array $addAddress, $message, $subject = '', $html =true)
    {
        $this->mail = new PHPMailer();
        $this->send($addAddress, $message, $subject, $html);
    }

    public function getConfigs()
    {
        return $this->configs;
    }

    public function setConfigs($configs)
    {
        $this->configs = $configs;
    }

    public function send(array $addAddress, $message, $subject = '', $html =true)
    {
        $this->validConfigs()
             ->setAddAddress($addAddress);

        $this->mail->Body = "{$message}";
        $this->mail->Subject = $subject;
        $this->mail->isHTML($html);

        if(!$this->mail->send()) {

            throw new \Exception($this->mail->ErrorInfo);
        }
        return true;
    }

    private function validConfigs()
    {
        if(empty($this->configs))
        {
            $this->setConfigs(config('mail.default'));
        }
        $this->config($this->configs);
        return $this;
    }

    private function config(array $configs)
    {
        $this->mail->isSMTP();
        foreach($configs as $key=>$config)
        {
            if($key == 'Username')
            {
                $this->mail->setFrom($config);
            }
            $this->mail->$key = $config;
        }
    }
    private function setAddAddress(array $array)
    {
        foreach($array as $key=>$r)
        {
            $this->mail->addAddress($key,$r);
        }

        return $this;
    }
}
