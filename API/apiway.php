<?php
session_start();
require_once("../Modeles/All.php");
require_once("../controleurs/fonctions.php");

try{
    if(!empty($_GET['demande'])){
        $url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
        switch($url[0]){
            case "checkValidity" : 
                $Api = new Api();
                $Api->getValidity($_GET['da'], $_GET['nbj'], $_SESSION['idReservationHebergement']);
                break;
            case "region" :
                $Api = new Api();
                $Api->getInfosRegions($_GET['idRegion']);
                break;
            default : throw new Exception ("La demande n'est pas valide, vérifiez l'url");
        }
    } else {
        throw new Exception ("Problème de récupération de données.");
    }
} catch(Exception $e){
    $erreur =[
        "message" => $e->getMessage(),
        "code" => $e->getCode()
    ];
    print_r($erreur);
}