<?php
require "traitement.php";

if (!empty($_POST['options'])){
    
    foreach($_POST['options'] as $key => $value){

        if (is_numeric($key) && is_numeric($value)){
            
            $Reservation = new ReservationHebergement();
            $isReservation = $Reservation->getReservationHebergementById($key);
            
            if(!empty($isReservation)){
                // Variable $_SESSION pour que l'utilisateur ne voit pas cette transmision de donnée
                $_SESSION['idReservationHebergement'] = $key;

                switch (intval($value)){
                    case 1:
                        break;
                    case 2:
                        break;
                    case 3:
                        header('location: ../vues/changeHebergement.php');
                        break;
                    case 4:
                        $Reservation->deleteReservationHebergement($key);
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

