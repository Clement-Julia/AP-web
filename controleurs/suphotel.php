<?php
require_once "traitement.php";
$hotel = new Hebergement();

if(!empty($_GET["libelle"])){
    try{
        $hotel->supHotel($_GET["libelle"]);
        header("location:../admin/supHotel.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}