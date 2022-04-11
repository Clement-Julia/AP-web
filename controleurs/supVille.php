<?php
require_once "traitement.php";

if(empty($_SESSION["supVille"])){
    header("location:../admin/modifVille.php");
}

$ville = new Ville($_SESSION["supVille"]);


if($_SESSION["idRole"] == 2){
    try{
        $ville->supVille($_SESSION["supVille"], $ville->getUuid());
        header("location:../admin/modifVille.php");
    }catch(exception $e){
        header("location:../admin/modifVille.php");
    }
}else{
    header("location:../admin/modifVille.php");
}