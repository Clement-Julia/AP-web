<?php
require_once "traitement.php";
$admin = new Admin();

if(!empty($_POST["email"])){
    $exist = $connexion->emailExiste($_POST["email"]);
    if($exist){
        try{
            $admin->updateUser($_POST["email"], $_POST["nom"], $_POST["prenom"], $_POST["age"], $_GET["id"]);
            header("location:../admin/modifUser.php");
        }catch(exception $e){
            header("location:../admin/index.php");
        }
    }
}else{
    header("location:../vues/index.php");
}