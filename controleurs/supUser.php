<?php
require_once "traitement.php";
$admin = new Admin();

if(!empty($_GET["id"])){
    $_SESSION["supUser"] = $_GET["id"];
}

if(empty($_SESSION["supUser"])){
    header("location:../admin/modifUser.php");
}

if($_SESSION["idRole"] == 2){
    try{
        $admin->supUser($_SESSION["supUser"]);
        header("location:../admin/modifUser.php");
    }catch(exception $e){
        header("location:../admin/modifUser.php");
    }
}else{
    try{
        $admin->supUser($_SESSION["supUser"]);
        header("location:../");
    }catch(exception $e){
        header("location:../");
    }
}
