<?php
require_once "traitement.php";
$admin = new Admin();

if($_SESSION["idRole"] == 2){
    try{
        $admin->supUser($_GET["id"]);
        header("location:../admin/modifUser.php");
    }catch(exception $e){
        header("location:../admin/modifUser.php");
    }
}else{
    try{
        $admin->supUser($_GET["id"]);
        header("location:../");
    }catch(exception $e){
        header("location:../");
    }
}
