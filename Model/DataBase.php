<?php
namespace Cac\Model;

abstract class DataBase extends Connection
{
    private $stmt;
    private $params;
    private $query;

    public function query($query)
    {
        $this->query= $this->query.$query;
        $this->stmt = $this->db->prepare($this->query);
        return $this;
    }

    public function bind($param, $value)
    {
        $this->params[$param] = $value;
        return $this;
    }

    public function execute()
    {
        $this->stmt->execute($this->params);
        return $this->claerParams();
    }

    public function results($class = null)
    {
        if (is_object($class)) {
            return $this->execute()
                   ->stmt->fetchAll(\PDO::FETCH_CLASS, get_class($class));
        }
        return $this->execute()->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function single($class = null)
    {
        if (is_object($class)) {
            return $this->execute()
                ->stmt->fetchObject(get_class($class));
        }
        return $this->execute()->stmt->fetch(\PDO::FETCH_OBJ);
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

    private function claerParams()
    {
        $this->params = [];
        return $this;
    }
}

