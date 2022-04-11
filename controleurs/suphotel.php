<?php
require_once "traitement.php";

if(empty($_SESSION["supHotel"])){
    header("location:../admin/modifVille.php");
}

$hotel = new Hebergement($_SESSION["supHotel"]);
$options = new Option();


if($_SESSION["idRole"] == 2){
    try{
        $hotel->supHotel($_SESSION["supHotel"], $hotel->getUuid());
        $options->supOptions($_SESSION["supHotel"]);
        header("location:../admin/modifHotel?success.php");
    }catch(exception $e){
        header("location:../admin/modifHotel?crash.php");
    }
}else{
    header("location:../vues/");
}