<?php
require_once "traitement.php";

if (!empty($_GET['jour']) &&
    !empty($_GET['mois']) &&
    !empty($_GET['annee'])
){

    // on tente de vérifier que la date rentrée est bien dans le futur
    $originDate = date("Y-m-d H:i:s");
    $targetDate = $_GET['annee'] . '-' . $_GET['mois'] . '-' . $_GET['jour'] . ' 0:0:0';
    $origin = new DateTime($originDate);
    $target = new DateTime($targetDate);
    $interval = $origin->diff($target);
    $diff = $interval->format('%R%a days');

    if ($diff < 0){
        // ERREUR, la date de réservation ne peut pas être dans le passé
    } else {
        
        $_SESSION['date']['start_travel'] = [
            'jour' => $_GET['jour'],
            'mois' => $_GET['mois'],
            'annee' => $_GET['annee']
        ];
    
        header('Location: ../vues/choixRegion.php');

    }


}