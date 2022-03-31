<?php
require_once "../controleurs/traitement.php";
$avis = new Avis();
if(!empty($_POST["rating"])){
    if($_POST["status"] == "update"){

        $avis->updateAvis($_POST["rating"], $_POST["commentaire"], $_GET["id"]);
        header("location:../vues/avis.php?success&update");

    }elseif($_POST["status"] == "delete" && empty($_GET["admin"])){
        $avis->supAvis($_GET["id"]);
        header("location:../vues/avis.php?success&delete");
    }

}elseif(!empty($_GET["status"]) && $_GET["status"] == "delete" && !empty($_GET["admin"])){
    $avis->supAvis($_GET["id"]);
    header("location:../admin/gestionAvis.php?libelle=".$_GET["libelle"]."&success");
}else {
    exit;
    header("location:../vues/avis.php?error=rating");
}