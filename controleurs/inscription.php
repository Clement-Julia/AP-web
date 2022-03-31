<?php
require_once "../controleurs/traitement.php";
$inscription = new Utilisateur();

if(!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["age"]) && !empty($_POST["mdp"]) && !empty($_POST["mdpVerif"]) && !empty($_POST["email"])){
    $erreur = $inscription->check_mdp_format($_POST["mdp"]);

    $exist = $inscription->emailExiste($_POST["email"]);

    $now = new DateTime();
    $date = new DateTime($_POST["age"]);
    
    if(empty($exist)){
        if(count($erreur) == 0 || $_POST["mdp"] != $_POST["mdpVerif"]){
            if($now->diff($date)->y >= 18){
                $inscription->inscription($_POST["email"], $_POST["mdp"], $_POST["nom"], $_POST["prenom"], $_POST["age"], 1, 1);
                header("location:../vues/");
            }else{
                header("location:../vues/inscription.php?erreur=age");
            }
        }else{
            header("location:../vues/inscription.php?erreur=mdp");
        }
    }else{
        header("location:../vues/inscription.php?erreur=email");
    }
}else {
    header("location:../vues/inscription.php?erreur=all");
}