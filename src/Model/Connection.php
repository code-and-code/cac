<?php
namespace Cac\Model;

class Connection
{
    protected $db;
    
    public function __construct()
    {
        $this->db = MysqlConnection::connect(config('app.database'));
    }
}