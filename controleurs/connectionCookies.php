<?php
require_once "../controleurs/traitement.php";

if(empty($_COOKIE["accept_cookies"]) && !empty($_POST["accept-cookies-modal"]) && $_POST["accept-cookies-modal"] == 1){
    $Utilisateur = new Utilisateur();
    $Utilisateur->acceptCookies();
    header("location:../vues/");
} else {
    header("location:../vues/");
}