<?php
require_once "traitement.php";

$region = new Region();

print_r($_POST);
if(!empty($_POST["latitude"]) && !empty($_POST["longitude"]) && !empty($_POST["description"])){
    try{
        $region->addRegion($_POST["region"], $_POST["description"], $_POST["longitude"], $_POST["latitude"]);
    
        header("location:../admin/addRegion.php?success");

    }catch(exception $e){
        header("location:../admin/addRegion.php?error=crash");
    }
}else{
    header("location:../admin/addRegion.php?error=all");
}