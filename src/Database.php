<?php

class Database
{

    private $host;
    private $name;
    private $user;
    private $password;

    public function __construct($host, $name, $user, $password)
    {
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->password = $password;
    }

    public function getConnection()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->name};";
        return new PDO($dsn, $this->user, $this->password);
    }
}
