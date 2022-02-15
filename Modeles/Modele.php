<?php

class Modele {


    protected function getBdd()
    {
        // production
        // $dsn = "mysql:host=ipssisqloocalaco.mysql.db;dbname=ipssisqloocalaco;charset=UTF8";
        // $username = "ipssisqloocalaco";
        // $password = "Ipssi2022loocalacool";

        // localhost
        $dsn = "mysql:host=localhost;dbname=ppe;charset=UTF8";
        $username = "root";
        $password = "";

        return new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

}