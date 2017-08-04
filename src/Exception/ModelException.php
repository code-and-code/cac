<?php namespace Cac\Exception;

class ModelException extends LogException
{
    public function __construct($message, $code = 0, \Exception $previous = null) {

        $message = "Ocorreu um erro ao tentar executar esta ação, Mensagem:  {$message}, Code : [{$code}]";
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
