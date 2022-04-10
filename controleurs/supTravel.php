<?php
require_once "traitement.php";
$admin = new Admin();
$travel = new ReservationVoyage();
$travelSteps = new ReservationHebergement();

if($_SESSION["idRole"] == 2){
    try{
        $travel->deleteBuildingTravelByTravelId($_GET["id"]);
        header("location:../admin/gestionVoyage.php?success");
    }catch(exception $e){
        header("location:../admin/gestionVoyage.php?crash");
    }
}else{
    header("location:../");
}
