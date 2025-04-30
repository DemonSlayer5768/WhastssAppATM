<?php

/*
 * Copyright (C) 2020 Juan Carlos
 *
 * Software desarrollado para el uso exclusivo de Coparmex Jalisco.
 */

/**
 * Description of database
 *
 * @author Juan Carlos
 */
class Database
{
    /*
    private $host = "localhost";
    private $db_name = "c1webempleo";
    private $username = "c1webempleo";
    private $password = "jvbKizX_GE97";
     * */

    private $host = "localhost";
    private $db_name = "coparmex";
    private $username = "root";
    private $password = "";

    // private $host = "192.168.0.101";
    // private $db_name = "coparmex";
    // private $username = "siicuser";
    // private $password = "newsiicuserid";


    public $conn;

    // get the database connection
    public function getConnection()
    {

        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
