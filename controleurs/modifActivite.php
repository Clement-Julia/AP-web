<?php
require_once "../controleurs/traitement.php";
$activite = new Activite();
$ville = new Ville();

if(!empty($_POST["activite"])&&!empty($_POST["currentLongitude"])&&!empty($_POST["currentLatitude"])&&!empty($_POST["description"])&&!empty($_POST["ville"])){
    
    try{
        $idActivite = $activite->getActiviteByName($_POST["activite"]);
        $ville = $ville->getVillebyName($_POST["ville"]);
        $activite->updateActiviteForCity($idActivite["idActivite"], $ville["idVille"], $_POST["currentLatitude"], $_POST["currentLongitude"], $_POST["description"], $_POST["oldLatitude"], $_POST["oldLongitude"]);
        header("location:../admin/modifActivite.php?success");
    }
    catch(exception $e)
    {
        header("location:../admin/modifActivite.php?activite=". $_POST["oldDescription"] ."&error=crash");
    }

}else {
    print_r($_POST);exit;
    header("location:../admin/modifActivite.php?activite=". $_POST["oldDescription"] ."&error=all");
}