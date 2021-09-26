<?php
require_once "traitement.php";

if (!empty($_POST['idHebergement']) && is_numeric($_POST['idHebergement']) && ($_SESSION['changeIdHebergement']) == $_POST['idHebergement']){

    $Hebergement = new Hebergement($_POST['idHebergement']);
    $Reservation = new ReservationHebergement($_SESSION['idReservationHebergement']);
    $prix = $Hebergement->getPrix() * $Reservation->getNbJours();

    $Reservation->updateReservationHebergement($Reservation->getCodeReservation(), $prix, $Reservation->getDateDebut(), $Reservation->getDateFin(), $Reservation->getNbJours(), $Reservation->getIdVoyage(), $Reservation->getIdUtilisateur(), $Hebergement->getIdHebergement(), $Reservation->getIdReservationHebergement());

    $ReservationVoyage = new ReservationVoyage();
    $ReservationVoyage->updatePrix($Reservation->getIdVoyage());

    header("location: ../vues/createTravel.php");

} else {
    header("location: ../vues/changeHebergementDescription.php?idHebergement=" . $_SESSION['changeIdHebergement']);
}