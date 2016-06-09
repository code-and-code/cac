<?php
namespace Cac\Model;

abstract class DataBase extends Connection
{
    private $stmt;

    public function query($query)
    {
        $this->stmt = $this->db->prepare($query);
        return $this;
    }

    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = \PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = \PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = \PDO::PARAM_NULL;
                    break;
                default:
                    $type = \PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);

        return $this;
    }

    public function execute()
    {
       $this->stmt->execute();
       return $this;
    }

    public function results()
    {
        return $this->execute()->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function single()
    {
        return $this->execute()->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
        return $this;
    }

    public function endTransaction()
    {
        $this->db->commit();
        return $this;
    }

    public function cancelTransaction()
    {
        $this->db->rollBack();
        return $this;
    }

    public function debugDumpParams()
    {
        return $this->stmt->debugDumpParams();
    }

    public function nameColumns()
    {
        return $this->execute()->stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

}
