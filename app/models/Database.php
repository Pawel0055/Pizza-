<?php

class Database
{
    protected $servername = 'localhost';
    protected $userename = 'root';
    protected $password = 'dupaSql';

    public $conn;

    public function __construct()
    {
        $this->conn = $this->connect();
    }

    private function connect()
    {
        $conn = new PDO("mysql:host=$this->servername;dbname=Pizza", $this->userename, $this->password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}