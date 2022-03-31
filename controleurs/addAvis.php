<?php
require_once "../controleurs/traitement.php";
$avis = new Avis();

if(!empty($_POST["rating"])){
    
    $avis->addAvis($_POST["rating"], $_POST["commentaire"], $_SESSION["idUtilisateur"], $_GET["id"]);
    header("location:../vues/avis.php?success");

}else {
    header("location:../vues/avis.php?error=rating");
}