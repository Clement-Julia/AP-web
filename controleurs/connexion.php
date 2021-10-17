<?php
require_once "traitement.php";
$connexion = new Utilisateur();

if(!empty($_POST["email"]) && !empty($_POST["mdp"])){
    $exist = $connexion->emailExiste($_POST["email"]);
    if(!empty($exist)){
        $return = $connexion->connexion($_POST["email"], $_POST["mdp"]);
        if($return["success"] === false){
            header("location:../vues/connexion.php?erreur=mdp");
        }else{
            header("location:../vues/index.php");
        }
    }else{
        header("location:../vues/connexion.php?erreur=email");
    }

}else {
    header("location:../vues/connexion.php?erreur=all");
}