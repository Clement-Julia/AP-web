<?php
require_once "traitement.php";
$ville = new Ville();

if(!empty($_POST["latitude"]) && !empty($_POST["longitude"])){
    try{
        $ville->updateVille($_POST["libelle"], $_POST["latitude"], $_POST["longitude"], $_POST["region"], $_POST["description"], $_GET["id"]);
        header("location:../admin/modifVille.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}