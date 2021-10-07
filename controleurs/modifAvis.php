<?php
require_once "../controleurs/traitement.php";
$avis = new Avis();

if(!empty($_POST["rating"])){
    if($_POST["status"] == "update"){

        $avis->updateAvis($_POST["rating"], $_POST["commentaire"], $_SESSION["idUtilisateur"], $_GET["id"]);
        header("location:../vues/");

    }elseif($_POST["status"] == "delete"){
        $avis->supAvis($_GET["id"]);
        header("location:../vues/");
    }

}else {
    header("location:../vues/inscription.php");
}