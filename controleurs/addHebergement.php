<?php
require_once "traitement.php";

if (!empty($_GET['idHebergement']) &&
    !empty($_GET['nbNuit']) &&
    !empty($_GET['continue']
    )
    ){
 // ------------------ a supprimer car entré en dur pour phase de test -------------------------------
        $_SESSION['idUtilisateur'] = 1;

        $dateDebut = new DateTime($_SESSION['date']['start_travel']['date_entiere']);
        $dateFin = new DateTime($dateDebut->format('Y-m-d') . $_GET['nbNuit'] . ' days');

        $Reservation = new ReservationHebergement();
        $lastDate = $Reservation->isItBookedForThisDate($dateDebut->format('Y-m-d'), $_GET['nbNuit'], $_GET['idHebergement']);

        if(!$lastDate) {

            // gestion de l'erreur, la date est déjà prise
            echo "la date est déjà prise";
            // et cette gestion là elle est vraiment cool !

        } else {

            $Hebergement = new Hebergement($_GET['idHebergement']);
            // $Ville = new Ville($Hebergement->getIdVille());
            $Voyage = new ReservationVoyage();

            // On recalcule le prix total de l'hébergement en faisant prix d'une nuit * nombre de nuit
            
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



            $_SESSION['date']['temp_date'] = $dateDebut->format('Y-m-d');
            $_SESSION['date']['start_travel']['date_entiere'] = $lastDate;
            $lastDate = explode('-', $lastDate);
            $_SESSION['date']['start_travel']['jour'] = $lastDate[2];
            $_SESSION['date']['start_travel']['mois'] = $lastDate[1];
            $_SESSION['date']['start_travel']['annee'] = $lastDate[0];

            // $_SESSION['voyage'][$_SESSION['index']] = [
            //     'idHebergement' => $Hebergement->getIdHebergement(),
            //     'nbJour' => $_GET['nbNuit'],
            //     'dateDebut' => $_SESSION['date']['temp_date'],
            //     'dateFin' => $_SESSION['date']['start_travel']['date_entiere'],
            //     'prix' => $Hebergement->getPrix() * $_GET['nbNuit'],
            //     'code_reservation' => 'azerty',
            //     'idVille' => $Hebergement->getIdVille(),
            //     'villeLatitude' => $Ville->getLatitude(),
            //     'villeLongitude' => $Ville->getLongitude()
            // ];
            // $_SESSION['index'] += 1;
            // $_SESSION['idRegion'] = $Hebergement->getIdRegionByIdHebergement($Hebergement->getIdHebergement());
                
        if ($_GET['continue'] == 1){
            header('location: ../vues/createTravel.php?continue=1');
        } else {
            header('location: ../vues/resumeTravel.php');
        }
        // echo "<pre>";
        // print_r($_SESSION['date']);
        // echo "</pre>";
        // echo "<pre>";
        // print_r($_SESSION['voyage']);
        // echo "</pre>";
        }

    }