<?php
require_once "traitement.php";
$connexion = new Utilisateur();
$now = new DateTime('NOW');

if(empty($_SESSION["try"])){
    $_SESSION["try"] = ["essai" => 3, "last-try" => $now];
}

if(!empty($_POST["email"]) && !empty($_POST["mdp"]) && !empty($_POST["g-recaptcha-response"])){
    if($_SESSION["try"]["essai"] != 0){
        $exist = $connexion->emailExiste($_POST["email"]);
        if(!empty($exist)){
            $return = $connexion->connexion($_POST["email"], $_POST["mdp"]);
            if($return["success"] === false){
                $_SESSION["try"]["last-try"] = $now;
                $_SESSION["try"]["essai"] = $_SESSION["try"]["essai"] - 1;
                header("location:../vues/connexion.php?erreur=login");
            }else{
                if(!empty($_POST["connection_cookies"]) && $_POST["connection_cookies"] == 1){
                    $connexion->setConnectionCookies();
                }
                header("location:../vues/index.php");
            }
        }else{
            header("location:../vues/connexion.php?erreur=login2");
        }
    }else{
        header("location:../vues/connexion.php?erreur=exceed");
    }
}else {
    header("location:../vues/connexion.php?erreur=all");
}