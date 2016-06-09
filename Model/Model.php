<?php
namespace Cac\Model;

abstract class Model extends DataBase
{
    public function all()
    {
        $this->query("SELECT * FROM {$this->table}");
        return $this->toObject($this->results());
    }

    public function find($id)
    {
        $this->query("SELECT * FROM {$this->table} WHERE id=:id")
              ->bind(":id", $id);

        return $this->fill($this->single());
    }

    public function where(array $where)
    {
        $this->query("SELECT * FROM {$this->table} WHERE {$where[0]} {$where[1]}   :{$where[0]}")
             ->bind(":".$where[0],$where[2]);

        return $this->toObject($this->results());
    }

    public function create(array $attributes)
    {
        $attributes = $this->hasTimeStamps($attributes);

        $fields           = implode(',', array_keys($attributes));
        $fieldsProtected  = ':' . implode(',:', array_keys($attributes));

        try{
            $this->query("INSERT INTO {$this->table} ({$fields}) VALUES ({$fieldsProtected})");
            $binds  = explode(',',$fields);

            foreach ($binds as $b)
            {
                $this->bind(':'.$b, $attributes[$b]);
            }
            
            $this->beginTransaction()
                  ->execute();

            $id = $this->lastInsertId();

            $this->endTransaction();

            return $this->find($id);

        } catch (\PDOException $e) {

            $this->cancelTransaction();

            return  "Ocorreu um erro ao tentar executar esta ação, Mensagem: ".$e->getMessage() ;
        }
    }

    public function update(array $attributes)
    {
        $attributes = $this->hasTimeStamps($attributes,'update');

        $fields           = implode(',', array_keys($attributes));
        $fieldsProtected  = explode(',',implode(',', array_keys($attributes)));

        foreach ($fieldsProtected as $f)
        {
            $temp[] = $f.'= :'.$f;
        }
        $fieldsProtected  = implode(',',$temp);

        try{
            $this->query("UPDATE {$this->table} SET {$fieldsProtected} WHERE id=:id");

            $binds  = explode(',',$fields);
            foreach ($binds as $b)
            {
                $this->bind(':'.$b, $attributes[$b]);
            }

            $this->bind(":id", $this->id)
                 ->beginTransaction()
                 ->execute()
                 ->endTransaction();
           
            return $this->find($this->id);

        } catch (\PDOException $e) {

            $this->cancelTransaction();
            return  "Ocorreu um erro ao tentar executar esta ação, Mensagem: ".$e->getMessage() ;
        }
    }

    public function delete()
    {
        try {

            $this->query("DELETE FROM {$this->table} WHERE id=:id")
                 ->bind(":id", $this->id)
                 ->beginTransaction()
                 ->execute()
                 ->endTransaction();

            return true;

        } catch (\PDOException $e) {

            $this->cancelTransaction();
            return  "Ocorreu um erro ao tentar executar esta ação, Mensagem: ".$e->getMessage() ;
        }
    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key=>$attribute)
        {
            $this->$key = $attribute;
        }

        return $this;
    }

    public function toArray()
    {
        $attributes = $this->getAttributes();

        foreach ($attributes as $attribute)
        {
            $values[$attribute] = $this->$attribute;
        }

        return $values;
    }

    public function getAttributes()
    {
        return $this->query("DESCRIBE {$this->table}")
                    ->nameColumns();
    }

    public function hasTimeStamps(array $attributes,$action = null)
    {
        $date =  date('Y-m-d H:i:s');

        switch ($action)
        {
            case 'update': $attributes['updated_at'] = $date;
            break;
            default:       $attributes['created_at'] = $date;
        }

        return $attributes;
    }

    public function toObject(array $attributes)
    {
        foreach ($attributes as $attribute)
        {
             foreach ($this->getAttributes() as $column)
             {
                 $obj[$column] =$attribute[$column];
             }
             $new  = new $this();
             $class[] = $new->fill($obj);
        }
        return $class;
    }
}
