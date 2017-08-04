<?php
namespace Cac\Model;

class MysqlConnection
{
    private $dbName = null, $dbHost = null, $dbPass = null, $dbUser = null;
    private static $instance = null;

    private function __construct($dbDetails = array()) {

        // Please note that this is Private Constructor

        $this->dbName = $dbDetails['dbname'];
        $this->dbHost = $dbDetails['host'];
        $this->dbUser = $dbDetails['username'];
        $this->dbPass = $dbDetails['password'];

        $db = "mysql:host=".$this->dbHost.";dbname=".$this->dbName['dbname'].";charset=utf8";

        // Your Code here to connect to database //
        $options = array(
            \PDO::ATTR_PERSISTENT    => true,
            \PDO::ATTR_ERRMODE       => \PDO::ERRMODE_EXCEPTION,
            //\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
        );

        // Create a new PDO instanace
        try{
            $this->dbh = new \PDO($db, $this->dbUser['username'], $this->dbPass, $options);
            return self::$instance;
        }
            // Catch any errors
        catch(\PDOException $e){

            $e->getMessage();
        }
    }

    public static function connect($dbDetails = array()) {

        // Check if instance is already exists
        if(self::$instance == null) {
            self::$instance = new MysqlConnection($dbDetails);
        }
        return self::$instance;

    }

    private function __clone() {
        // Stopping Clonning of Object
    }

    private function __wakeup() {
        // Stopping unserialize of object
    }


}