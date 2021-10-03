<?php
require_once "header.php";
$ReservationVoyage = new ReservationVoyage();
$BuildingTravelId = $ReservationVoyage->getIdBuildingTravelByUserId($_SESSION['idUtilisateur']);
$ReservationVoyage = new ReservationVoyage($BuildingTravelId);



?>

<div id="resume-main-container">
    <div>
        <?php if (!empty($_GET['building'])){ ?>
            <div>Voici le dernier voyage que nous recensons pour vous :</div>
        <?php } else { ?>
            <div>Votre voyage</div>
        <?php } ?>
        <?php 
            if($BuildingTravelId != null){
                $index = 1;
                foreach ($ReservationVoyage->getReservationHebergement() as $reservationHebergement){
                    $infos = $reservationHebergement->getHebergementById($reservationHebergement->getIdHebergement());
                    ?>
                    <div class="mx-3 my-3">
                        <div>Etape : <?=$index?></div>
                        <div>Ville : <?=$infos['villeNom']?></div>
                        <div>Hébergement : <?=$infos['nomHebergement']?></div>
                        <div>Description hébergement : <?=$infos['description']?></div>
                        <div>Date d'arrivée : <?=$reservationHebergement->getDateDebut()?></div>
                        <div>Date de départ : <?=$reservationHebergement->getDateFin()?></div>
                        <div>Code réservation : <?=$reservationHebergement->getCodeReservation()?></div>
                        <div>Prix : <?=$reservationHebergement->getPrix()?></div>
                    </div>
                    <?php
                    $index++;
                }
            }
        ?>
    </div>
    <div>Prix total : <?=$ReservationVoyage->getPrix();?></div>
    <div>Moyen de paiement</div>
    <?php if (!empty($_GET['building'])){ ?>
            <form action="../controleurs/deleteBuildingTravel.php" method="POST">
                <button class="btn btn-success btn-sm">Continuer ce voyage</button>
                <button name="cancel" value="1" class="btn btn-secondary btn-sm">Annuler ce voyage</button>
            </form>
    <?php } else { ?>
            <form action="../controleurs/deleteBuildingTravel.php" method="POST">
                <button name="validate" value="1" class="btn btn-success btn-sm">Valider et Payer</button>
                <button name="cancel" value="1" class="btn btn-secondary btn-sm">Annuler ce voyage</button>
                <button class="btn btn-primary btn-sm">Retour au résumé</button>
            </form>
    <?php } ?>
</div>

<?php
require_once "footer.php";
?>