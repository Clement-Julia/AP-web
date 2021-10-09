<?php
require_once "traitement.php";

if (!empty($_GET['idHebergement']) &&
    !empty($_GET['nbNuit']) &&
    !empty($_GET['continue']
    )
    ){

        // on regarde si une réservation existe déjà, si non, on prend la date de SESSION['date'], sinon on cherche la date de fin de la dernière réservation du voyage
        $ReservationVoyage = new ReservationVoyage();
        $isBuilding = $ReservationVoyage->getIsBuildingByUserId($_SESSION['idUtilisateur']);
        if (empty($isBuilding)){

            $dateDebut = new DateTime($_SESSION['date']);
            $dateFin = new DateTime($_SESSION['date'] . "+" . $_GET['nbNuit'] . " days");

        } else {
            
            $lastReservation = $ReservationVoyage->getLastReservationHebergement($isBuilding['idReservationVoyage']);
            $dateDebut = new DateTime($lastReservation['dateFin']);
            $dateFin = new DateTime($lastReservation['dateFin'] . "+" . $_GET['nbNuit'] . " days");
            $_SESSION['date'] = "";

        }

        $Reservation = new ReservationHebergement();
        $lastDate = $Reservation->isItBookedForThisDate($dateDebut->format('Y-m-d'), $_GET['nbNuit'], $_GET['idHebergement']);

        if(!$lastDate) {

            // gestion de l'erreur, la date est déjà prise
            header('location: ../vues/hebergementDescription.php?idHebergement=' . $_GET['idHebergement'] . "&error=1");

        } else {

            $Hebergement = new Hebergement($_GET['idHebergement']);
            $Voyage = new ReservationVoyage();
            
            // On créer un voyage
            $isBuilding = $Voyage->getIsBuildingByUserId($_SESSION['idUtilisateur']);

            $prixNuitsHebergement = $Hebergement->getPrix() * $_GET['nbNuit'];
            
            if (!$isBuilding == null){
                $idVoyage = $isBuilding['idReservationVoyage'];
                $Voyage = new ReservationVoyage($idVoyage);
            } else {
                $idVoyage = $Voyage->insertBaseTravel($prixNuitsHebergement, 'azerty', $_SESSION['idUtilisateur'], true);
            }
            
            $newTravelPrice = $Voyage->getPrix() + $prixNuitsHebergement;
            $Voyage->updateTravelPrice($newTravelPrice, $Voyage->getIdReservationVoyage());

            $Reservation->insertReservationHebergement($_SESSION['idUtilisateur'], $idVoyage, 'azerty', $prixNuitsHebergement, $dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d'), $_GET['nbNuit'], $Hebergement->getIdHebergement());

            if ($_GET['continue'] == 1){
                header('location: ../vues/createTravel.php');
            } else {
                header('location: ../vues/resumeTravel.php');
            }

        }

    }