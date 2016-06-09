<?php
namespace Cac\Model;

class Connection
{
    protected $db;
    private   $config;
    private   $error;

    public function __construct()
    {
        $this->setDb();
        $this->getDb();
    }

    private function getDb()
    {
        $db = "mysql:host=".$this->config['host'].";dbname=".$this->config['dbname'];
        $options = array(
            \PDO::ATTR_PERSISTENT    => true,
            \PDO::ATTR_ERRMODE       => \PDO::ERRMODE_EXCEPTION
        );

        // Create a new PDO instanace
        try{
            $this->db = new \PDO($db, $this->config['username'], $this->config['password'], $options);
        }
            // Catch any errors
        catch(\PDOException $e){
            $this->error = $e->getMessage();
        }
    }

    private function setDb()
    {
        $this->config =  get_config('database');
    }
}