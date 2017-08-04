<?php
namespace Cac\Model;

class Connection
{
    protected $db;
    private   $error;

    public function __construct()
    {
        //$this->getDb(config('app.database'));
        $this->db = MysqlConnection::connect(config('app.database'));
    }

    private function getDb(array $config)
    {
        $db = "mysql:host=".$config['host'].";dbname=".$config['dbname'].";charset=utf8";
        $options = array(
            \PDO::ATTR_PERSISTENT    => true,
            \PDO::ATTR_ERRMODE       => \PDO::ERRMODE_EXCEPTION,
            //\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
        );

        // Create a new PDO instanace
        try{
            $this->db = new \PDO($db, $config['username'], $config['password'], $options);
            //$this->db->exec("SET CHARACTER SET utf8");
        }
            // Catch any errors
        catch(\PDOException $e){
            $this->error = $e->getMessage();
        }
    }
}