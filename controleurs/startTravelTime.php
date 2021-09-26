<?php
require_once "traitement.php";

// (SECURITE) on vérifie qu'on récupère une variable et on vérifie que c'est bien une date
if (!empty($_POST['date']) && isValidDate($_POST['date'])
){

    $actualDate = new DateTime();
    $postDate = new DateTime($_POST['date']);
    $diff = $actualDate->diff($postDate);

    // Si invert est égal à 0, la date est dans le présent. Le cas spécial de la date du jour est gérée ici.
    if ($diff->invert == 0 || ($diff->invert == 1 && $diff->d == 0)){

        $ReservationVoyage = new ReservationVoyage();
        $isBuilding = $ReservationVoyage->getIsBuildingByUserId($_SESSION['idUtilisateur']);

        $_SESSION['date'] = $_POST['date'];

        if (!empty($isBuilding)){
            header('Location: ../vues/resumeTravel.php?building=1');
        } else {
            header('Location: ../vues/choixRegion.php');
        }
        
    } else {
        header('Location: ../vues/index.php');
    }


} else {
    header('Location: ../vues/index.php');
}