<?php
require "traitement.php";

if (!empty($_POST['options'])){
    
    foreach($_POST['options'] as $key => $value){

        if (is_numeric($key) && is_numeric($value)){
            
            $Reservation = new ReservationHebergement();
            $isReservation = $Reservation->getReservationHebergementById($key);
            if(!empty($isReservation) && ($_SESSION['idUtilisateur'] == $isReservation['idUtilisateur'])){

                // Variable $_SESSION pour que l'utilisateur ne voit pas cette transmision de donnée
                $_SESSION['idReservationHebergement'] = $key;

                switch (intval($value)){
                    case 1:
                        header('location: ../vues/changeDate.php');
                        break;
                    case 2:
                        header('location: ../vues/changeVille.php');
                        break;
                    case 3:
                        header('location: ../vues/changeHebergement.php');
                        break;
                    case 4:
                        $Reservation->deleteReservationHebergement($key);
                        $ReservationVoyage = new ReservationVoyage();
                        $BuildingTravelId = $ReservationVoyage->getIdBuildingTravelByUserId($_SESSION['idUtilisateur']);
                        $ReservationVoyage = new ReservationVoyage($BuildingTravelId);
                        if(count($ReservationVoyage->getReservationHebergement()) == 0){
                            $ReservationVoyage->deleteBuildingWithIdReservationVoyage($BuildingTravelId);
                            header('location: ../vues/index.php');
                        } else {
                            header('location: ../vues/createTravel.php');
                        }
                        break;
                    default:
                        break;
                }
            } else {
                header('location: ../vues/createTravel.php');
            }
        } else {
            header('location: ../vues/createTravel.php');
        }
    }
} else {
    header('location: ../vues/createTravel.php');
}

