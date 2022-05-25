<?php
require_once "header.php";

if(!empty($_SESSION['idUtilisateur'])){

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
            <div class="form-container mt-5">
                <?php if (!empty($_GET['building'])){ ?>
                    <div class="card mb-3 form-container">
                        <div class="card-header text-center text-light">
                            <h4>
                                Voici le dernier voyage que nous recensons pour vous : <br>
                                <?= $_SESSION["title"] ?>
                            </h4>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="card mb-3 form-container">
                        <div class="card-header text-center text-light"><h3><?= $_SESSION["title"] ?></h3></div>
                    </div>
                <?php } ?>
                <?php 
                    if($buildingTravelId != null){
                        $index = 1;
                        foreach ($ReservationVoyage->getReservationHebergement() as $reservationHebergement){
                            $infos = $reservationHebergement->getHebergementById($reservationHebergement->getIdHebergement());
                            ?>
                            <div class="mx-3 my-3 card form-container text-light">
                                <div class="card-header"><h6>Etape : <?=$index?></h6></div>
                                <div class="card-body">
                                    <div>Ville : <?=$infos['villeNom']?></div>
                                    <div>Hébergement : <?=$infos['nomHebergement']?></div>
                                    <div>Description hébergement : <?=$infos['description']?></div>
                                    <div>Date d'arrivée : <?=dateToFr($reservationHebergement->getDateDebut())?></div>
                                    <div>Date de départ : <?=dateToFr($reservationHebergement->getDateFin())?></div>
                                    <div>Code réservation : <?=$reservationHebergement->getCodeReservation()?></div>
                                    <div>Prix : <?=$reservationHebergement->getPrix()?>€</div>
                                </div>
                            </div>
                            <?php
                            $index++;
                        }
                    }
                ?>
            </div>
            <div class="card form-container my-3">
                <div class="card-header text-center text-light"><h5>Prix total : <?=$ReservationVoyage->getPrix();?> €</h5></div>
            </div>
            
            <?php if (!empty($_GET['building'])){ ?>
                    <form action="../controleurs/deleteBuildingTravel.php" method="POST">
                        <div class="card form-container mb-3">
                            <div class="card-body d-flex justify-content-center">
                                <button class="mx-2 btn btn-success">Continuer ce voyage</button>
                                <button type="button" class="mx-2 btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete">Supprimer ce voyage</button>
                            </div>
                        </div>

                        <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDelete" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-dark" id="exampleModalLabel">Attention !</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-dark">
                                        Vous êtes sur le point d'abandonner votre voyage et de le supprimer. Si vous continuez ce voyage sera supprimé et deviendra inaccessible. Êtes-vous sûr ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Retour</button>
                                        <button type="submit" name="cancel" value="1" class="btn btn-danger">Valider et supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            <?php } else { ?>

                    <div id="paiement-cb" class="card form-container">
                        <div class="card-header text-center text-light"><h5>Paiement</h5></div>
                        <div class="card-body d-flex flex-column" style="align-items:center">
                            <div class="d-flex flex-column" style="background-color:white;padding:15px;border-radius:5px;border:1px solid black; box-shadow:2px 2px 5px gray;">
                                <div style="display:flex; justify-content:center;">
                                    <img class="img-fluid" src="../assets/src/img/card1.png" alt="" style="width: 50px; background-color:transparent;">
                                    <img class="img-fluid" src="../assets/src/img/card2.jpg" alt="" style="width: 50px; background-color:transparent;">
                                    <img class="img-fluid" src="../assets/src/img/card3.png" alt="" style="width: 50px; background-color:transparent;">
                                    <img class="img-fluid" src="../assets/src/img/card4.jpg" alt="" style="width: 50px; background-color:transparent;">
                                </div>
                                <div>
                                    <label for="card-number">Numéro de carte :</label><br>
                                    <input type="text" id="card-number" name="card-number" style="width: 350px;">
                                </div>
                                <div style="display:flex;justify-content:space-between;">
                                    <div>
                                        <label for="date-peremption">Date péremption :</label><br>
                                        <input type="text" id="date-peremption" name="date-peremption" style="width: 100px;">
                                    </div>
                                    <div>
                                        <label for="cvc">Cryptogramme : </label><br>
                                        <input type="text" id="cvc" name="cvc" style="width: 100px;">
                                    </div>
                                </div>
                                <div>
                                    <label for="fullname">Nom du détenteur de la carte :</label><br>
                                    <input type="text" id="fullname" name="fullname" style="width: 350px;">
                                </div>
                            </div>
                        </div>
                    </div>


                    <form action="../controleurs/deleteBuildingTravel.php" method="POST">
                        <div class="card my-3 form-container">
                            <div class="card-body d-flex justify-content-center">
                                <button name="validate" value="1" class="mx-2 btn btn-success btn-sm">Valider et Payer</button>
                                <button type="button" class="mx-2 btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete">Supprimer ce voyage</button>
                                <button class="mx-2 btn btn-primary btn-sm">Retour</button>
                            </div>
                        </div>

                        <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDelete" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Attention !</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                    <div class="modal-body">
                                        Vous êtes sur le point d'abandonner votre voyage et de le supprimer. Si vous continuez ce voyage sera supprimé et deviendra inaccessible. Êtes-vous sûr ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Retour</button>
                                        <button type="submit" name="cancel" value="1" class="btn btn-danger">Valider et supprimer</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            <?php } ?>
        </div>
    <?php
    } else {
        header('location: createTravel.php?idRegion=' . $_SESSION['idRegion']);
    }

} else { ?>
    <div class="alert alert-warning">Vous devez être connecté pour accéder à ce contenu</div>
<?php } ?>


<?php
require_once "footer.php";
?>