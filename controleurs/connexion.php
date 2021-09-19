<?php
require_once "traitement.php";
$connexion = new Utilisateur();


if(!empty($_POST["email"]) && !empty($_POST["mdp"])){
    
    $connexion->connexion($_POST["email"], $_POST["mdp"]);
    header("location:../vues/");


}else {
    header("location:../vues/connexion.php");
}