<?php
require_once "traitement.php";
$hotel = new Hebergement();
$options = new Option();

if(empty($_SESSION["supHotel"])){
    header("location:../admin/modifVille.php");
}

if(!empty($_GET["libelle"])){
    try{
        $hotel->supHotel($_SESSION["supHotel"]);
        $id = $hotel->getHotelbyName($_SESSION["supHotel"]);
        $options->supOptions($id["idHebergement"]);
        header("location:../admin/modifHotel.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}