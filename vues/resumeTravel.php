<?php
require_once "header.php";
$ReservationVoyage = new ReservationVoyage();
$buildingTravelId = $ReservationVoyage->getIdBuildingTravelByUserId($_SESSION['idUtilisateur']);

if($buildingTravelId != null){

    $ReservationVoyage = new ReservationVoyage($buildingTravelId);
    $_SESSION['idReservationVoyage'] = $buildingTravelId;
?>

    <div id="resume-main-container">
        <?php
        if(isset($_SESSION['resultats']) && count($_SESSION['resultats']) > 0){
            ?>
            <div class="alert alert-warning my-3">
                Il semblerait que durant la création de votre voyage, <?=count($_SESSION['resultats']) > 1 ? "ces hébergements ont été réservés et validés par un autre utilisateur" : "cet hébergement a été réservé et validé par un autre utilisateur" ?> sur les dates de votre choix : <br>
                <?php
                foreach($_SESSION['resultats'] as $reservation){
                    $Reservation = new ReservationHebergement($reservation);
                    $Hebergement = new Hebergement($Reservation->getIdHebergement());
                    ?>
                    _ <?= $Hebergement->getLibelle() ?><br>
                    <?php
                }
                ?>
                Votre voyage ne peut donc pas être finalisé.
            </div>
            <?php
        }
        ?>
        <div>
            <?php if (!empty($_GET['building'])){ ?>
                <div class="card my-3">
                    <div class="card-header text-center"><h4>Voici le dernier voyage que nous recensons pour vous :</h4></div>
                </div>
            <?php } else { ?>
                <div class="card my-3">
                    <div class="card-header text-center"><h3>Votre voyage</h3></div>
                </div>
            <?php } ?>
            <?php 
                if($buildingTravelId != null){
                    $index = 1;
                    foreach ($ReservationVoyage->getReservationHebergement() as $reservationHebergement){
                        $infos = $reservationHebergement->getHebergementById($reservationHebergement->getIdHebergement());
                        ?>
                        <div class="mx-3 my-3 card">
                            <div class="card-header"><h6>Etape : <?=$index?></h6></div>
                            <div class="card-body">
                                <div>Ville : <?=$infos['villeNom']?></div>
                                <div>Hébergement : <?=$infos['nomHebergement']?></div>
                                <div>Description hébergement : <?=$infos['description']?></div>
                                <div>Date d'arrivée : <?=$reservationHebergement->getDateDebut()?></div>
                                <div>Date de départ : <?=$reservationHebergement->getDateFin()?></div>
                                <div>Code réservation : <?=$reservationHebergement->getCodeReservation()?></div>
                                <div>Prix : <?=$reservationHebergement->getPrix()?></div>
                            </div>
                        </div>
                        <?php
                        $index++;
                    }
                }
            ?>
        </div>
        <div class="card my-3">
            <div class="card-header text-center"><h5>Prix total : <?=$ReservationVoyage->getPrix();?></h5></div>
        </div>
        <div class="card my-3">
            <div class="card-header text-center"><h6>Moyen de paiement</h6></div>
            <div class="card-body">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paiement" id="radio-1">
                    <label class="form-check-label" for="radio-1">
                    Carte de paiement
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paiement" id="radio-2">
                    <label class="form-check-label" for="radio-2">
                    Paypal
                    </label>
                </div>
            </div>
        </div>
        <?php if (!empty($_GET['building'])){ ?>
                <form action="../controleurs/deleteBuildingTravel.php" method="POST">
                    <div class="card">
                        <div class="card-body d-flex justify-content-center">
                            <button class="mx-2 btn btn-success btn-sm">Continuer ce voyage</button>
                            <button name="cancel" value="1" class="mx-2 btn btn-secondary btn-sm">Supprimer ce voyage</button>
                        </div>
                    </div>
                </form>
        <?php } else { ?>
                <form action="../controleurs/deleteBuildingTravel.php" method="POST">
                    <div class="card">
                        <div class="card-body d-flex justify-content-center">
                            <button name="validate" value="1" class="mx-2 btn btn-success btn-sm">Valider et Payer</button>
                            <button name="cancel" value="1" class="mx-2 btn btn-secondary btn-sm">Supprimer ce voyage</button>
                            <button class="mx-2 btn btn-primary btn-sm">Retour</button>
                        </div>
                    </div>
                </form>
        <?php } ?>
    </div>
<?php
} else {
    header('location: createTravel.php?idRegion=' . $_SESSION['idRegion']);
}
?>


<?php
require_once "footer.php";
?>