<?php
require_once "traitement.php";
$ville = new Ville();
$info = $ville->getVillebyName($_GET["libelle"]);

if(empty($_SESSION["supVille"])){
    header("location:../admin/modifVille.php");
}

if(!empty($_GET["libelle"]) && is_string($_GET["libelle"])){
    try{
        $ville->supVille($_SESSION["supVille"], $info["uuid"]);
        header("location:../admin/modifVille.php");
    }catch(exception $e){
        header("location:../admin/modifVille.php");
    }
}else{
    header("location:../admin/modifVille.php");
}