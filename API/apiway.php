<?php
session_start();
require_once("../Modeles/All.php");
require_once("../controleurs/fonctions.php");

try{
    if(!empty($_GET['demande'])){
        $url = explode("/", filter_var($_GET['demande'],FILTER_SANITIZE_URL));
        switch($url[0]){
            case "checkValidity" : 
                if(!empty($_GET['da']) && isValidDate($_GET['da']) && !empty($_GET['nbj']) && is_numeric($_GET['nbj']) && !empty($_SESSION['idReservationHebergement']) && is_numeric($_SESSION['idReservationHebergement'])
                ){
                    $Api = new Api();
                    $Api->getValidity($_GET['da'], $_GET['nbj'], $_SESSION['idReservationHebergement']);
                } else {
                    throw new Exception ("La demande n'est pas valide, vérifiez l'url");
                }
                break;
            case "region" :
                if(!empty($_GET['idRegion']) && is_numeric($_GET['idRegion'])){
                    $Api = new Api();
                    $Api->getInfosRegions($_GET['idRegion']);
                } else {
                    throw new Exception ("La demande n'est pas valide, vérifiez l'url");
                }
                break;
            case "checkBooking":
                if(!empty($_GET['da']) && isValidDate($_GET['da']) && is_numeric($_GET['nbj']) && !empty($_SESSION['idHebergement']) && is_numeric($_SESSION['idHebergement'])
                ){
                    $Api = new Api();
                    $Api->getHebergementBooking($_GET['da'], $_GET['nbj'], $_SESSION['idHebergement']);
                } else {
                    throw new Exception ("La demande n'est pas valide, vérifiez l'url");
                }
                break;
            case "image":
                if(!empty($_GET['name'])){
                    $Api = new Api();
                    $Api->supImage($_GET['name']);
                } else {
                    throw new Exception ("La demande n'est pas valide, vérifiez l'url");
                }
                break;
            case "favoris":
                if(!empty($_SESSION['idHebergement']) && is_numeric($_SESSION['idHebergement']) && !empty($_SESSION['idUtilisateur']) && is_numeric($_SESSION['idUtilisateur']) && empty($_GET['idHebergement'])){
                    $Api = new Api();
                    $Api->insertOrDeleteLikeHebergement($_SESSION['idHebergement'], $_SESSION['idUtilisateur']);
                }
                if((!empty($_GET['idHebergement']) && is_numeric($_GET['idHebergement']))){
                    $Api = new Api();
                    $Api->insertOrDeleteLikeHebergement($_GET['idHebergement'], $_SESSION['idUtilisateur']);
                }
                break;
            case "activites":
                if(!empty($_SESSION['idVille']) && is_numeric($_SESSION['idVille'])){
                    $Api = new Api();
                    $Api->getAllActivitesByIdVille($_SESSION['idVille']);
                }
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
}