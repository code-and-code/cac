<?php namespace Cac\Exception;

use Cac\Support\Log;

class ModelException extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null) {

        $message = "Ocorreu um erro ao tentar executar esta ação, Mensagem:  {$message}, Code : [{$code}]";
        Log::logMsg($message,'error');

        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
