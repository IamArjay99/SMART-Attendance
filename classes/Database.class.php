<?php

class Database {
    private $host     = "localhost";
    private $user     = "root";
    private $password = "";
    private $dbname   = "smart-attendance";

    protected function connect() {
        $dsn = "mysql:host=" .$this->host. ";dbname=" .$this->dbname;
        $conn = new PDO($dsn, $this->user, $this->password);
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $conn;
    }

}