<?php

namespace Jorge\ReabastecimentoDoCartao\Model;


class BaseModel
{
    private $pdo;


    public function __construct()
    {
        try {
            $_dbHostname = $_ENV['DB_HOST'];
            $_dbName = $_ENV['DB_NAME'];
            $_dbUsername = $_ENV['DB_USER'];
            $_dbPassword = $_ENV['DB_PASSWORD'];


            $this->pdo = new \PDO("mysql:host=$_dbHostname;
              dbname=$_dbName;", $_dbUsername, $_dbPassword);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Exception $e) {
            echo "Falha ao conectar: " . $e->getMessage();
        }
    }

    public function returnConnection()
    {
        return $this->pdo;
    }
}
