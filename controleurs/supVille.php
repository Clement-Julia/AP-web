<?php
require_once "traitement.php";
$ville = new Ville();
$info = $ville->getVillebyName($_GET["libelle"]);

if(!empty($_GET["libelle"])){
    try{
        $ville->supVille($_GET["libelle"], $info["uuid"]);
        header("location:../admin/modifVille.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}