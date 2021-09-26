<?php
require_once "../controleurs/traitement.php";
$inscription = new Utilisateur();

if(!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["age"]) && !empty($_POST["mdp"]) && !empty($_POST["mdpVerif"]) && !empty($_POST["email"])){
    
    $inscription->inscription($_POST["email"], $_POST["mdp"], $_POST["nom"], $_POST["prenom"], $_POST["age"], 1);
    header("location:../vues/");

}else {
    header("location:../vues/inscription.php");
}