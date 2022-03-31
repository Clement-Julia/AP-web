<?php
require_once "traitement.php";
$region = new Region();
// print_r($_POST);exit;
if(!empty($_POST["description"]) && is_string($_POST["description"])){
    try{
        $region->updateRegion($_GET["id"], $_POST["description"]);
        header("location:../admin/modifRegion.php?success");
    }catch(exception $e){
        header("location:../admin/modifRegion.php?error=crash");
    }
}else{
    header("location:../admin/modifRegion.php?error=all");
}