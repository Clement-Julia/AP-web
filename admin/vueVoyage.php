<?php
require_once "headerAdmin.php";

$limit = 10;
$ReservationVoyage = new ReservationVoyage();
$voyage = $ReservationVoyage->getVoyageById($_GET["id"]);
?>

<div class="container-fluid">
    <?php
    $index = 1;
    foreach($voyage as $etapes){
        ?>
        <div class="row justify-content-center">

            <div class="card card-travel mx-3 my-2">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Étape : <?=$index?></h6>
                </div>
                <div class="card-body">
                    <div>
                        <span class="fw-bold font-italic">Ville :</span>
                        <?=$etapes['ville']?>
                    </div>
                    <div>
                        <span class="fw-bold font-italic">Hébergement :</span>
                        <?=$etapes['hebergement']?>
                    </div>
                    <div>
                        <span class="fw-bold font-italic">Description hébergement :</span>
                        <?=$etapes['description']?>
                    </div>
                    <div>
                        <span class="fw-bold font-italic">Date d'arrivée :</span>
                        <?=dateToFr($etapes['dateDebut'])?>
                    </div>
                    <div>
                        <span class="fw-bold font-italic">Date de départ :</span>
                        <?=dateToFr($etapes['dateFin'])?>
                    </div>
                    <div>
                        <span class="fw-bold font-italic">Code réservation :</span>
                        <?=$etapes['code']?>
                    </div>
                    <div>
                        <span class="fw-bold font-italic">Prix :</span>
                        <?=$etapes['prix']?> €
                    </div>
                </div>
            </div>

        </div>
        <?php
        $index++;
    }
    ?>
    <a href="gestionVoyage.php" class="btn btn-secondary my-5"><i class="fas fa-arrow-left"></i></a>
</div>

<?php
require_once "footerAdmin.php";