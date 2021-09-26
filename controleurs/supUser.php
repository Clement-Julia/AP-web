<?php
require_once "traitement.php";
$admin = new Admin();

try{
    $admin->supUser($_GET["id"]);
    header("location:../admin/modifUser.php");
}catch(exception $e){
    header("location:../admin/index.php");
}