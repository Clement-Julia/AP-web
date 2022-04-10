<?php
require_once "traitement.php";

$region = new Region();

if(!empty($_POST["currentLatitude"]) && is_numeric($_POST["currentLatitude"]) && !empty($_POST["currentLongitude"]) && is_numeric($_POST["currentLongitude"])){
    try{
        $region->addRegion($_POST["region"], $_POST["description"], $_POST["longitude"], $_POST["latitude"]);
    
        header("location:../admin/addRegion.php?success");

    }catch(exception $e){
        header("location:../admin/addRegion.php?error=crash");
    }
}else{
    header("location:../admin/addRegion.php?error=all");
}