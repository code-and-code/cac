<?php
namespace Cac\Support;

use Cac\Exception\ValidationException;
use Cac\Model\Model;

class Validation
{

    public static function requiredBasic($rules, $attributes)
    {
        $errors = 0;

        foreach ($attributes as $key => $attribute)
        {
            if(in_array($key,$rules))
            {
                if(is_null($attributes[$key]))
                {

                    if(empty($attribute))
                    {
                        alert('error',"o campo {$key} é obrigatório");
                        $errors++;
                    }
                }
            }
        }
        back('Erro de validação','warning');
    }

    public static function requireModel($class, $attributes)
    {
        $errors = 0;

        if($class instanceof Model)
        {
            if(property_exists($class,'requested')) {

                foreach ($attributes as $key => $attribute) {

                    if (in_array($key, $class->requested)) {

                        if(empty($attribute))
                        {
                            alert('error',"o campo {$key} é obrigatório");
                            $errors++;
                        }
                    }
                }

                if($errors > 0)
                {
                    throw new ValidationException('Erro de validação');
                }
            }
            else
            {
                throw new ValidationException("Compos REQUIRIDOS,não foi configurado no CLASS: ".get_class($class));
            }
        }
        else
        {
            throw new ValidationException(get_class($class)."Não uma ESTANCIA de MODEL");
        }
    }
}

