<?php

class Modele {


    protected function getBdd()
    {
        $dsn = "mysql:host=localhost;dbname=appweb;charset=UTF8";
        $username = "root";
        $password = "";
        return new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        
        // $dsn = "mysql:host=ipssisqloocalaco.mysql.db;dbname=ipssisqloocalaco;charset=UTF8";
        // $username = "ipssisqloocalaco";
        // $password = "Ipssi2022loocalacool";
        // return new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

}