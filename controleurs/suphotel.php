<?php
require_once "traitement.php";
$hotel = new Hebergement();
$options = new Option();

if(!empty($_GET["libelle"])){
    try{
        $hotel->supHotel($_GET["libelle"]);
        $id = $hotel->getHotelbyName($_GET["libelle"]);
        $options->supOptions($id["idHebergement"]);
        header("location:../admin/modifHotel.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}