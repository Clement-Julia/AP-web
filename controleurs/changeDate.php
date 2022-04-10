<?php
require_once "traitement.php";
// (SECURITE) On vérifie que tout existe puis si les dates sont bien des dates et si la réservation est bien un chiffre
if (
    !empty($_POST['dateDebut']) && 
    isValidDate($_POST['dateDebut']) &&
    !empty($_POST['dateFin']) && 
    isValidDate($_POST['dateFin']) &&
    !empty($_SESSION['idReservationHebergement']) &&
    is_numeric($_SESSION['idReservationHebergement'])
    ){

        $ReservationHebergement = new ReservationHebergement($_SESSION['idReservationHebergement']);
        $Hebergement = new Hebergement($ReservationHebergement->getIdHebergement());
        $Api = new Api();

        if($_SESSION['idUtilisateur'] == $ReservationHebergement->getIdUtilisateur()){

            // Si les dates envoyées étaient pas dans l'ordre chronologique ... on les inverse
            if($_POST['dateDebut'] > $_POST['dateFin']){
                $dateTemp = $_POST['dateDebut'];
                $_POST['dateDebut'] = $_POST['dateFin'];
                $_POST['dateFin'] = $dateTemp;
            }

            $origin = new DateTime($_POST['dateDebut']);
            $target = new DateTime($_POST['dateFin']);
            $nbJours = $origin->diff($target)->d;

            // On va boucler sur chacun des jours afin de vérifier si un voyageur autre que nous a déjà réservé sur ces dates
            $mesReservations = [];

            for($i = 0; $i < $nbJours; $i++){

                $date = new DateTime($_POST['dateDebut'] . '+' . $i . ' days');
                $booking = $Api->getReservBetweenDate($date->format("Y-m-d"), $Hebergement->getIdHebergement());
                if(!empty($booking)){
                    header('location: ../vues/changeDate.php?error=date-de-voyage-deja-prise');
                }

                $reserve = $Api->getOurOwnReserv($date->format("Y-m-d"), $_SESSION['idUtilisateur'], $_SESSION['idReservationHebergement'], $date->format("Y-m-d"));
                
                if (!empty($reserve) && !in_array($reserve, $mesReservations)){
                    $mesReservations[] = $reserve;
                }
                
            }

            // Si mon voyage n'empiète que sur un seul de mes voyages en construction
            if(count($mesReservations) == 1){

                $ReservationTemp = new ReservationHebergement(($mesReservations[0]['idReservationHebergement']));

                //Le voyage n'est pas complètement écrasé, sinon il l'est (else)
                if($ReservationTemp->getDateDebut() < $_POST['dateDebut'] || $ReservationTemp->getDateFin() > $_POST['dateFin']){

                    if($ReservationTemp->getDateDebut() < $_POST['dateDebut']){
                        $ReservationTemp->setDateFin($_POST['dateDebut']);
                    } else {
                        $ReservationTemp->setDateDebut($_POST['dateFin']);
                    }

                    $originTemp = new DateTime($ReservationTemp->getDateDebut());
                    $targetTemp = new DateTime($ReservationTemp->getDateFin());
                    $nbJoursTemp = $originTemp->diff($targetTemp)->d;
                    if($nbJoursTemp == 0){
                        $nbJoursTemp++;
                    }

                    $HebergementTemp = new Hebergement($ReservationTemp->getIdHebergement());

                    $ReservationTemp->updateReservationHebergement(
                        $ReservationTemp->getCodeReservation(),
                        ($HebergementTemp->getPrix() * $nbJoursTemp),
                        $ReservationTemp->getDateDebut(),
                        $ReservationTemp->getDateFin(),
                        $nbJoursTemp,
                        $ReservationTemp->getIdVoyage(),
                        $ReservationTemp->getIdUtilisateur(),
                        $ReservationTemp->getIdHebergement(),
                        $ReservationTemp->getIdReservationHebergement()
                    );
                } else {
                    $ReservationTemp->deleteReservationHebergement($ReservationTemp->getIdReservationHebergement());
                }

            } else if (count($mesReservations) > 1){
                // Si mon voyage empiète sur plusieurs de mes voyages en construction
                foreach($mesReservations as $item){
                    $ReservationTemp = new ReservationHebergement($item['idReservationHebergement']);
                    
                    if($ReservationTemp->getDateDebut() < $_POST['dateDebut'] || $ReservationTemp->getDateFin() > $_POST['dateFin']){

                        $HebergementTemp = new Hebergement($ReservationTemp->getIdHebergement());

                        if($ReservationTemp->getDateDebut() < $_POST['dateDebut']){
                            $ReservationTemp->setDateFin($_POST['dateDebut']);
                        } else {
                            $ReservationTemp->setDateDebut($_POST['dateFin']);
                        }

                        $originTemp = new DateTime($ReservationTemp->getDateDebut());
                        $targetTemp = new DateTime($ReservationTemp->getDateFin());
                        $nbJoursTemp = $originTemp->diff($targetTemp)->d;
                        if($nbJoursTemp == 0){
                            $nbJoursTemp++;
                        }

                        $ReservationTemp->updateReservationHebergement(
                            $ReservationTemp->getCodeReservation(),
                            ($HebergementTemp->getPrix() * $nbJoursTemp),
                            $ReservationTemp->getDateDebut(),
                            $ReservationTemp->getDateFin(),
                            $nbJoursTemp,
                            $ReservationTemp->getIdVoyage(),
                            $ReservationTemp->getIdUtilisateur(),
                            $ReservationTemp->getIdHebergement(),
                            $ReservationTemp->getIdReservationHebergement()
                        );

                    } else {
                        $ReservationTemp->deleteReservationHebergement($ReservationTemp->getIdReservationHebergement());
                    }
                }

            }

            $ReservationHebergement->updateReservationHebergement(
                $ReservationHebergement->getCodeReservation(),
                ($Hebergement->getPrix() * $nbJours),
                $_POST['dateDebut'],
                $_POST['dateFin'],
                $nbJours,
                $ReservationHebergement->getIdVoyage(),
                $ReservationHebergement->getIdUtilisateur(),
                $ReservationHebergement->getIdHebergement(),
                $ReservationHebergement->getIdReservationHebergement()
            );

            $ReservationVoyage = new ReservationVoyage();
            $ReservationVoyage->updatePrix($ReservationHebergement->getIdVoyage());

            header('location: ../vues/createTravel.php');

        }
    } else {
        header('location: ../vues/changeDate.php?error=dates-de-voyage-incorrectes');
    }