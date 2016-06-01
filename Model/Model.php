<?php
namespace Cac\Model;

abstract class Model extends Connection
{

    /**
     * @return array
     */

    public function all()
    {
        $query = "SELECT * FROM {$this->table}";
        return $this->getDb()->query($query)->fetchAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $stmt = $this->getDb()->prepare("SELECT * FROM {$this->table} WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $this->fill($stmt->fetch());
    }

    /**
     * @param array $attributes
     * @return mixed|string
     */
    public function create(array $attributes)
    {
        $fields           = implode(',', array_keys($attributes));
        $fieldsProtected  = ':' . implode(',:', array_keys($attributes));

        try{
            $stmt   = $this->getDb()->prepare("INSERT INTO {$this->table} ({$fields}) VALUES ({$fieldsProtected})");
            $binds  = explode(',',$fields);
            foreach ($binds as $b)
            {
                $stmt->bindParam(':'.$b, $attributes[$b]);
            }
            $stmt->execute();
            $this->find($this->getDb()->lastInsertId());

        } catch (\PDOException $e) {

            return  "Ocorreu um erro ao tentar executar esta ação, Mensagem: ".$e->getMessage() ;
        }
    }

    /**
     * @param array $attributes
     * @param $id
     * @return string
     */
    public function update(array $attributes,$id)
    {
        $fields           = implode(',', array_keys($attributes));
        $fieldsProtected  = explode(',',implode(',', array_keys($attributes)));

        foreach ($fieldsProtected as $f)
        {
            $temp[] = $f.'= :'.$f;
        }
        $fieldsProtected  = implode(',',$temp);

        try{
            $stmt   = $this->getDb()->prepare("UPDATE {$this->table} SET {$fieldsProtected} WHERE id=:id");

            $binds  = explode(',',$fields);
            foreach ($binds as $b)
            {
                $stmt->bindParam(':'.$b, $attributes[$b]);
            }

            $stmt->bindParam(':id',$id);
            $stmt->execute();

        } catch (PDOException $e) {

            return  "Ocorreu um erro ao tentar executar esta ação, Mensagem: ".$e->getMessage() ;
        }
    }

    /**
     * @param $id
     * @return bool|string
     */
    public function delete($id)
    {
        try {
            $stmt = $this->getDb()->prepare("DELETE FROM {$this->table} WHERE id=:id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {

            return  "Ocorreu um erro ao tentar executar esta ação, Mensagem: ".$e->getMessage() ;
        }

    }

    /**
     * @param array $attributes
     * @return mixed
     */
    public function fill(array $attributes)
    {
        $name_class = get_class($this);
        $class      = new $name_class();

        foreach ($attributes as $key=>$attribute)
        {
            $class->$key = $attribute;
        }

        return $class;
    }

}




