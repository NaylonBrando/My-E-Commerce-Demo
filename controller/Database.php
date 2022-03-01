<?php

class Database{

    private $conn;


    public function connectMysqli(){

        $this->conn = new mysqli("localhost","root","","ecommerce");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        return $this->conn;
    }



}