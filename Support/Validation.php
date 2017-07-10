<?php
namespace Cac\Support;

use Cac\Exception\ValidationException;
use Cac\Model\Model;

class Validation
{
    public static function requiredBasic($rules, $attributes)
    {
        foreach ($attributes as $key => $attribute)
        {
            if(in_array($key,$rules))
            {
                alert('error',"O campo {$key} é obrigatório");
            }
        }
        back('Erro de validação','warning');
    }

    public static function requireModel($class, $attributes)
    {
        if($class instanceof Model)
        {
            if(property_exists($class,'requested')) {

                foreach ($attributes as $key => $attribute) {
                    if (in_array($key, $class->requested)) {

                        alert('error',"o campo {$key} é obrigatório");
                    }
                }
                throw new ValidationException('Erro de validação');
            }

            throw new ValidationException("Compos REQUIRIDOS,não foi configurado no CLASS: ".get_class($class));

        }
        throw new ValidationException(get_class($class)."Não uma ESTANCIA de MODEL");
    }
}

