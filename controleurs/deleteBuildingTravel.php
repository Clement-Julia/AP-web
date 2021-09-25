<?php
require_once "traitement.php";

// On vérifie si on récupère la valeur du $_POST
// (SECURITE) On vérifie si c'est bien un chiffre que l'on récupère 

if (!empty($_POST['cancel']) && is_numeric($_POST['cancel'])){

    // L'utilisateur ne continue pas son voyage, on le supprime et on le redirige
    $ReservationVoyage = new ReservationVoyage();
    $ReservationVoyage->deleteBuildingTravelByUserId($_SESSION['idUtilisateur']);
    header("location: ../vues/choixRegion.php");

}