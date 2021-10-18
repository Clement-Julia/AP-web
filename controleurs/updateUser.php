<?php
require_once "traitement.php";
$user = new Utilisateur();
$admin = new Admin();

if($_GET["update"] == "info"){
    if(!empty($_POST["nom"]) && !empty($_POST["prenom"]) && !empty($_POST["age"])){
        try{
            $admin->updateUser_info($_POST["nom"], $_POST["prenom"], $_POST["age"], $_SESSION["idUtilisateur"]);
            header("location:../../vues/profil.php?success=info");
        }catch(exception $e){
            header("location:../../vues/profil.php?error=crash");
        }
    }else{
        header("location:../../vues/profil.php?error=all");
    }
}elseif($_GET["update"] == "co"){
    if(!empty($_POST["email"]) || !empty($_POST["current_mdp"]) && !empty($_POST["new_mdp"]) && !empty($_POST["new_verif"])){
        if(!empty($_POST["email"])){
            $exist = $user->emailExiste($_POST["email"]);
            if(empty($exist)){
                try{
                    $admin->updateUser_email($_POST["email"], $_SESSION["idUtilisateur"]);
                    header("location:../../vues/profil.php?success=email");
                }catch(exception $e){
                    header("location:../../vues/profil.php?error=crash");
                }
            }else{
                header("location:../../vues/profil.php?error=email");
            }
        }

        if(!empty($_POST["current_mdp"]) && !empty($_POST["new_mdp"]) && !empty($_POST["new_verif"])){
            if($_POST["new_mdp"] == $_POST["new_verif"]){
                try{
                    $admin->updateUser_mdp($_POST["new_mdp"], $_SESSION["idUtilisateur"]);
                    header("location:../../vues/profil.php?success=mdp");
                }catch(exception $e){
                    header("location:../../vues/profil.php?error=crash");
                }
            }else{
                header("location:../../vues/profil.php?error=mdp");
            }
        }        
    }else{
        header("location:../../vues/profil.php?error=all");
    }
}