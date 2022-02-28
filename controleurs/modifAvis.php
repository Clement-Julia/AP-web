<?php
require_once "../controleurs/traitement.php";
$avis = new Avis();

if(!empty($_POST["rating"])){
    if($_POST["status"] == "update"){

        $avis->updateAvis($_POST["rating"], $_POST["commentaire"], $_SESSION["idUtilisateur"], $_GET["id"]);
        header("location:../vues/avis.php");

    }elseif($_POST["status"] == "delete" && empty($_GET["admin"])){
        $avis->supAvis($_GET["id"]);
        header("location:../vues/avis.php");
    }

}elseif(!empty($_GET["status"]) && $_GET["status"] == "delete" && !empty($_GET["admin"])){
    $avis->supAvis($_GET["id"]);
    header("location:../admin/gestionAvis.php?libelle=".$_GET["libelle"]."&success");
}else {
    header("location:../vues/avis.php");
}