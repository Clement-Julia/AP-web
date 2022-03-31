<?php
require_once "../controleurs/traitement.php";
$avis = new Avis();

$allUnpostedOpinions = $avis->getHebergementbynonAvis($_SESSION['idUtilisateur']);
$success = false;

foreach($allUnpostedOpinions as $unpostedOpinion){

    if($_GET["id"] == $unpostedOpinion['idHebergement'] && $_GET['dateFin'] == $unpostedOpinion['dateFin']){
        if(
            !empty($_POST["rating"]) &&
            isValidDate($_GET['dateFin']) &&
            is_numeric($_GET['id'])
        ){
            $avis->addAvis($_POST["rating"], $_POST["commentaire"], $_SESSION["idUtilisateur"], $_GET["id"], $_GET['dateFin']);
            $success = true;
        }else {
            header("location:../vues/avis.php?error=type");
        }

    }

}
if($success){
    header("location:../vues/avis.php?success");
} else {
    header("location:../vues/avis.php?error=rating");
}