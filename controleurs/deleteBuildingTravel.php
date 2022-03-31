<?php
require_once "traitement.php";

// On vérifie si on récupère la valeur du $_POST
// (SECURITE) On vérifie si c'est bien un chiffre que l'on récupère 

if (!empty($_POST['cancel']) && is_numeric($_POST['cancel'])){

    // L'utilisateur ne continue pas son voyage, on le supprime et on le redirige
    $ReservationVoyage = new ReservationVoyage();
    $ReservationVoyage->deleteBuildingTravelByUserId($_SESSION['idUtilisateur']);
    header("location: ../vues/index.php");
    
} else if(!empty($_POST['validate']) && is_numeric($_POST['validate'])){
    // On récupère le voyage et on va vérifier si chacune des réservations est encore disponible.
    $ReservationVoyage = new ReservationVoyage();
    $idReservationVoyage = $ReservationVoyage->getIdBuildingTravelByUserId($_SESSION['idUtilisateur']);
    $Voyage = new ReservationVoyage($idReservationVoyage);

    if($_SESSION['idUtilisateur'] == $Voyage->getIdUtilisateur()){

        $ReservationsHebergements = $Voyage->getReservationHebergement();



        // On va boucler sur chacun des jours afin de vérifier si un voyageur autre que nous a déjà réservé sur ces dates
        $_SESSION['resultats'] = [];
        $Api = new Api();

        foreach($ReservationsHebergements as $reservation){
            for($i = 0; $i < $reservation->getNbJours(); $i++){

                $date = new DateTime($reservation->getDateDebut() . '+' . $i . ' days');
                $booking = $Api->getReservBetweenDate($date->format("Y-m-d"), $reservation->getIdHebergement());
                if(!empty($booking)){
                    $_SESSION['resultats'][] = $reservation->getIdReservationHebergement();
                    break;
                }
                
            }

        }

        if(count($_SESSION['resultats']) > 0){
            header("location: ../vues/resumeTravel.php");
        } else {
            $Voyage->updateIsBuilding($idReservationVoyage, False);
            header("location: ../vues/index.php");
        }
    } else {
        header("location: ../vues/resumeTravel.php");
    }
} else {
    header("location: ../vues/createTravel.php");
}