<?php
namespace Cac\Model;

use Cac\Exception\ModelException;

abstract class Model extends DataBase
{
    public function all()
    {
        $this->query("SELECT * FROM {$this->table}");
        return $this->results($this);
    }
    public function find($id)
    {
        $this->query("SELECT * FROM {$this->table} WHERE id=:id")
            ->bind(":id", $id);
        return $this->single($this);
    }
    public function where($name,$operator,$value)
    {
        $this->query("SELECT * FROM {$this->table} WHERE {$name} {$operator}   :{$name}")
            ->bind(":" . $name, $value);
        return $this;
    }
    public function andWhere($name,$operator,$value)
    {
        $rad = rand(5, 15);
        $this->query(" AND {$name} {$operator}   :{$rad}")
            ->bind(":" . $rad, $value);
        return $this;
    }
    public function create(array $attributes)
    {
        $attributes = $this->hasTimeStamps($attributes);
        $fields = implode(',', array_keys($attributes));
        $fieldsProtected = ':' . implode(',:', array_keys($attributes));
        try {
            $this->query("INSERT INTO {$this->table} ({$fields}) VALUES ({$fieldsProtected})");
            $binds = explode(',', $fields);
            foreach ($binds as $b) {
                $this->bind(':' . $b, $attributes[$b]);
            }
            $this->execute();
            return $this->find($this->lastInsertId());
        } catch (\PDOException $e) {

            throw new ModelException($e->getMessage(),null);
        }
    }
    public function update(array $attributes)
    {
        $attributes = $this->hasTimeStamps($attributes, __METHOD__);
        $fields = implode(',', array_keys($attributes));
        $fieldsProtected = explode(',', implode(',', array_keys($attributes)));
        foreach ($fieldsProtected as $f) {
            $temp[] = $f . '= :' . $f;
        }
        $fieldsProtected = implode(',', $temp);
        try {
            $this->query("UPDATE {$this->table} SET {$fieldsProtected} WHERE id=:id");
            $binds = explode(',', $fields);
            foreach ($binds as $b) {
                $this->bind(':' . $b, $attributes[$b]);
            }
            $this->bind(":id", $this->id)
                ->execute();
            return $this->find($this->id);
        } catch (\PDOException $e) {

            throw new ModelException($e->getMessage(),null);
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

            throw new ModelException($e->getMessage(),null);
        }
    }
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $attribute) {
            $this->$key = $attribute;
        }
        return $this;
    }
    public function toArray()
    {
        $attributes = $this->getAttributes();
        foreach ($attributes as $attribute) {
            $values[$attribute] = $this->$attribute;
        }
        return $values;
    }
    public function getAttributes()
    {
        return $this->query("DESCRIBE {$this->table}")
            ->nameColumns();
    }
    public function hasTimeStamps(array $attributes, $action = null)
    {
        $date = date('Y-m-d H:i:s');
        switch ($action) {
            case 'Cac\Model\Model::update':
                $attributes['updated_at'] = $date;
                break;
            default:
                $attributes['created_at'] = $date;
        }
        return $attributes;
    }
    public function get()
    {
        return $this->results($this);
    }
    public function first()
    {
        return $this->single($this);
    }

    public function hasOne($class, $column = null)
    {
        $class  = new $class();
        (is_null($column))   ? $column  = $this->columnRelationship($class) : $column = $column;
        $result = $class->where('id','=',$this->$column)->first();
        return  $result;
    }
    public function hasMany($class)
    {
        $class  = new $class();
        $column = $this->columnRelationship($this);
        $result = $class->where($column,'=',$this->id)->get();
        return  $result;
    }

    public function belongsTo($class,$column = null,$id = null)
    {
        $class  = new $class();
        (is_null($column))   ? $column  = $this->columnRelationship($this) : $column = $column;
        (is_null($id))       ? $id      = $this->id : $id = $this->$id;

        $result = $class->where($column,'=',$id)->first();

        return  $result;
    }

    private function columnRelationship($class)
    {
        $name = explode('\\',get_class($class));
        $name = strtolower(end($name)).'_id';
        return $name;
    }
}
