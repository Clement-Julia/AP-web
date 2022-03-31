<?php
require_once "../controleurs/traitement.php";
$activite = new Activite();
$ville = new Ville();

print_r($_POST);exit;

if(!empty($_POST["activite"])&&!empty($_POST["currentLongitude"])&&!empty($_POST["currentLatitude"])&&!empty($_POST["description"])&&!empty($_POST["ville"])){
    
    try{
        $idActivite = $activite->getActiviteByName($_POST["activite"]);
        $ville = $ville->getVillebyName($_POST["ville"]);
        $activite->addActiviteForCity($idActivite["idActivite"], $ville["idVille"], $_POST["currentLatitude"], $_POST["currentLongitude"], $_POST["description"]);
        header("location:../admin/addActivite.php?success");
    }
    catch(exception $e)
    {
        header("location:../admin/addActivite.php?error=crash");
    }

}else {
    header("location:../admin/addActivite.php?error=all");
}