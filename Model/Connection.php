<?php
namespace Cac\Model;

class Connection
{
    protected $db;
    private   $error;

    public function __construct()
    {
        $this->getDb(get_config('database'));
    }

    private function getDb(array $config)
    {
        $db = "mysql:host=".$config['host'].";dbname=".$config['dbname'];
        $options = array(
            \PDO::ATTR_PERSISTENT    => true,
            \PDO::ATTR_ERRMODE       => \PDO::ERRMODE_EXCEPTION
        );

        // Create a new PDO instanace
        try{
            $this->db = new \PDO($db, $config['username'], $config['password'], $options);
        }
            // Catch any errors
        catch(\PDOException $e){
            $this->error = $e->getMessage();
        }
    }
}