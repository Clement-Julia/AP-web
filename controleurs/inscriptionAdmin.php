<?php
require_once "../controleurs/traitement.php";
$inscription = new Utilisateur();
print_r($_POST);
if(!empty($_POST["nom"]) && is_string($_POST["nom"]) && !empty($_POST["prenom"]) && is_string($_POST["prenom"]) && !empty($_POST["age"]) && isValidDate($_POST["age"]) && !empty($_POST["mdp"]) && !empty($_POST["mdpVerif"]) && !empty($_POST["email"])){
    $erreur = $inscription->check_mdp_format($_POST["mdp"]);

    $exist = $inscription->emailExiste($_POST["email"]);

    $now = new DateTime();
    $date = new DateTime($_POST["age"]);
    
    if(empty($exist)){
        if(count($erreur) == 0 || $_POST["mdp"] != $_POST["mdpVerif"]){
            if($now->diff($date)->y >= 18){
                $inscription->inscription($_POST["email"], $_POST["mdp"], $_POST["nom"], $_POST["prenom"], $_POST["age"], 1, 1);
                header("location:../admin/addUser.php?success");
            }else{
                header("location:../admin/addUser.php?erreur=age");
            }
        }else{
            header("location:../admin/addUser.php?erreur=mdp");
        }
    }else{
        header("location:../admin/addUser.php?erreur=email");
    }
}else {
    header("location:../admin/addUser.php?erreur=all");
}