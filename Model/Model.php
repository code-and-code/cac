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
        return $this->dbquery($query)->fetchAll();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id=:id");
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
            $stmt   = $this->db->prepare("INSERT INTO {$this->table} ({$fields}) VALUES ({$fieldsProtected})");
            $binds  = explode(',',$fields);
            foreach ($binds as $b)
            {
                $stmt->bindParam(':'.$b, $attributes[$b]);
            }
            $this->db->beginTransaction();
            $stmt->execute();
            $this->db->commit();
            return $this->find($this->db->lastInsertId());

        } catch (\PDOException $e) {

            $this->db->rollback();
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
            $stmt   = $this->db->prepare("UPDATE {$this->table} SET {$fieldsProtected} WHERE id=:id");

            $binds  = explode(',',$fields);
            foreach ($binds as $b)
            {
                $stmt->bindParam(':'.$b, $attributes[$b]);
            }

            $stmt->bindParam(':id',$id);
            $this->db->beginTransaction();
            $stmt->execute();
            $this->db->commit();

        } catch (PDOException $e) {

            $this->db->rollback();
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
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id=:id");
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
            $attributes[] = $this->$attribute;
        }

        return $attributes;
    }

    public function getAttributes()
    {
        $stmt = $this->db->prepare("DESCRIBE {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

}
