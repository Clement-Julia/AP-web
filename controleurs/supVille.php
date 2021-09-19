<?php
require_once "traitement.php";
$ville = new Ville();

if(!empty($_GET["libelle"])){
    try{
        $ville->supVille($_GET["libelle"]);
        header("location:../admin/supVille.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}