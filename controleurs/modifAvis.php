<?php
require_once "../controleurs/traitement.php";
$avis = new Avis();

if(!empty($_POST["rating"])){
    if($_POST["status"] == "update"){

        $boolean = $avis->isThisOpinionBelongToHim($_GET["id"]);
        if($boolean){
            $avis->updateAvis($_POST["rating"], $_POST["commentaire"], $_GET["id"]);
            header("location:../vues/avis.php?success&update");
        } else {
            header("location:../vues/avis.php?error=notBelongToYou");
        }

    }elseif($_POST["status"] == "delete" && empty($_GET["admin"])){
        $avis->supAvis($_GET["id"]);
        header("location:../vues/avis.php?success&delete");
    }

}elseif(!empty($_GET["status"]) && $_GET["status"] == "delete" && !empty($_GET["admin"])){
    $avis->supAvis($_GET["id"]);
    header("location:../admin/gestionAvis.php?libelle=".$_GET["libelle"]."&success");
}else {
    header("location:../vues/avis.php?error=rating");
}