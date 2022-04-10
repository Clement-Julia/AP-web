<?php
require_once "traitement.php";
$user = new Utilisateur();
$admin = new Admin();

if(!empty($_POST["email"])){
    $exist = $user->emailExiste($_POST["email"]);
    $now = new DateTime();
    $date = new DateTime($_POST["age"]);
    if($exist){
        try{
            if($now->diff($date)->y >= 18){
                $admin->updateUser($_POST["email"], $_POST["nom"], $_POST["prenom"], $_POST["age"], $_GET["id"]);
                header("location:../admin/modifUser.php?success");
            }else{
                header("location:../admin/modifUser.php?erreur=age");
            }
        }catch(exception $e){
            header("location:../admin/index.php");
        }
    }
}else{
    header("location:../vues/index.php");
}