<?php
require_once "traitement.php";

if (!empty($_GET['idHebergement']) &&
    !empty($_GET['nbNuit']) &&
    !empty($_GET['continue'])
    ){

        // print_r($_SESSION['date']); exit;

        $Reservation = new ReservationHotel();
        $lastDate = $Reservation->isItBookedForThisDate($_SESSION['date']['start_travel']['date_entiere'], $_GET['nbNuit'], $_GET['idHebergement']);

        if(!$lastDate) {

            // gestion de l'erreur, la date est déjà prise
            echo "la date est déjà prise";

        } else {

            $Hebergement = new Hebergement($_GET['idHebergement']);
            $Ville = new Ville($Hebergement->getIdVille());
            if (empty($_SESSION['index'])){
                $_SESSION['index'] = 0;
            }

            $_SESSION['date']['temp_date'] = $_SESSION['date']['start_travel']['date_entiere'];
            $_SESSION['date']['start_travel']['date_entiere'] = $lastDate;
            $lastDate = explode('-', $lastDate);
            $_SESSION['date']['start_travel']['jour'] = $lastDate[2];
            $_SESSION['date']['start_travel']['mois'] = $lastDate[1];
            $_SESSION['date']['start_travel']['annee'] = $lastDate[0];

            $_SESSION['voyage'][$_SESSION['index']] = [
                'idHebergement' => $Hebergement->getIdHebergement(),
                'nbJour' => $_GET['nbNuit'],
                'dateDebut' => $_SESSION['date']['temp_date'],
                'dateFin' => $_SESSION['date']['start_travel']['date_entiere'],
                'prix' => $Hebergement->getPrix() * $_GET['nbNuit'],
                'code_reservation' => 'azerty',
                'idVille' => $Hebergement->getIdVille(),
                'villeLatitude' => $Ville->getLatitude(),
                'villeLongitude' => $Ville->getLongitude()
            ];
            $_SESSION['index'] += 1;
            $_SESSION['idRegion'] = $Hebergement->getIdRegionByIdHebergement($Hebergement->getIdHebergement());

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