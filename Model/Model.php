<?php
namespace Cac\Model;

abstract class Model extends Connection
{
    protected $table;

    public function all()
    {
        $query = "SELECT * FROM {$this->table}";
        return $this->db->query($query)->fetchAll();
    }

    
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id=:id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }

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
            $stmt->execute();
        } catch (Exception $e) {

            return  "Ocorreu um erro ao tentar executar esta ação, Mensagem: ".$e->getMessage() ;
        }
    }

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
            $stmt->execute();

        } catch (Exception $e) {

            return  "Ocorreu um erro ao tentar executar esta ação, Mensagem: ".$e->getMessage() ;
        }
    }


    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id=:id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            return true;

        } catch (Exception $e) {

            return  "Ocorreu um erro ao tentar executar esta ação, Mensagem: ".$e->getMessage() ;
        }

    }

}




