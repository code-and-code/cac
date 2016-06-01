<?php
namespace Cac\Model;

class Connection
{
    protected $db;
    private   $config;

    public function __construct()
    {
        $this->setDb();
        $this->db  = $this->getDb();
    }

    private function getDb()
    {
        $db = new \PDO("mysql:host=".$this->config['host'].";dbname=".$this->config['dbname'],
                        $this->config['username'],
                        $this->config['password']);


        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $db;
    }

    private function setDb()
    {
        $this->config =  get_config('database');
    }
}