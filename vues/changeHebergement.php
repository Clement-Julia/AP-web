<?php
require_once "header.php";

if(!empty($_SESSION['idUtilisateur'])){

    // (SECURITE) On vérifie que le paramètre récupéré est bien du type INT attendu
    if (is_numeric($_SESSION['idReservationHebergement'])){

        $Reservation = new ReservationHebergement($_SESSION['idReservationHebergement']);
        
        // L'utilisateur actuel est "propriétaire" de la réservation qu'il tente de modifier
        if($Reservation->getIdUtilisateur() == $_SESSION['idUtilisateur']){
            
            // Si $_GET['idVille'], alors on vient de la page changeVille (choix 2 lors de la modification d'une étape) sinon on fait comme d'habitude
            if (!empty($_GET['idVille']) && is_numeric($_GET['idVille'])){
                $idVille = $_GET['idVille'];

                $Ville = new Ville();
                $idRegionVille = $Ville->getRegion($idVille)['idRegion'];
                $idRegionHebergement = $Reservation->getIdRegionByHebergementId($Reservation->getIdHebergement());

                // Si l'utilisateur tente de changer manuellement le $_GET['idVille']
                if($idRegionVille != $idRegionHebergement){
                    header('location: changeVille.php');
                }

            } else {
                $idVille = $Reservation->getIdVilleByHebergementId($Reservation->getIdHebergement());
            }

            // Il faut vérifier et afficher uniquement les hôtels libres sur cet interval
            $dateDebut = new DateTime($Reservation->getDateDebut());
            $Ville = new Ville($idVille);
            $idRegion = $Ville->getIdRegion();
            $Hebergs = $Ville->getFreeHebergement($dateDebut, $Ville->getIdVille());
            
            if(!empty($Hebergs)){

                ?>

                <style>
                    body{
                        background-image: url('../assets/src/img/background/HebergementDes.jpg');
                        background-size: cover;
                        background-repeat: no-repeat;
                    }
                    .card-header{
                        color: black;
                    }
                    #navbar{
                        background-color: #27272773 !important;
                        backdrop-filter: blur(12px);
                    }
                </style>

                <div id="hv-container">
                    <div id="hv-back-button-container">
                        <a href="<?= isset($_GET['idVille']) ? "changeVille.php" : "createTravel.php" ?>" class="btn btn-sm btn-secondary back-button text-light"><</a>
                    </div>
                    <div id="choose-hebergement">
                        <div class="row d-flex">
                        <?php
                        $isAtLeastOne = false;
                        foreach ($Hebergs as $item)
                        {
                            $HebergementTemp = new Hebergement($item[1]->getIdHebergement());
                            $Image = new Images($HebergementTemp->getUuid());
                            
                            if($item[0] != "indisponible" && $item[2] >= $Reservation->getNbJours() && $item[1]->getIdHebergement() != $Reservation->getIdHebergement()){
                                $isAtLeastOne = true;
                                ?>

                            <div class="col-xs-12 col-sm-6 col-md-6 col-xl-6 col-xxl-4 d-flex align-items-stretch flex-wrap">
                                <div id="<?= $item[1]->getIdHebergement()?>" data-hebergement="1" data-id="<?= $item[1]->getIdHebergement()?>" data-name="<?= $item[1]->getLibelle()?>" data-lat="<?= $item[1]->getLatitude()?>" data-lng="<?= $item[1]->getLongitude()?>" data-price="<?=$item[1]->getPrix()?>" data-zoom="12" class="card ct-a js-marker">
                                    <img class="img-fluid" alt="100%x280" src="<?=$Image->getBanniere()?>">
                                    <div class="card-body hv-text-hebergement">
                                        <h6 class="card-title"><?= $item[1]->getLibelle()?></h6>
                                        <p><?= coupe_phrase($item[1]->getDescription())?></p>
                                        <div class="d-flex justify-content-between">
                                            <span>Prix : <?= $item[1]->getPrix()?> €</span>
                                            <span></span>
                                        </div>
                                    </div>
                                    <div class="card-footer text-muted"><?=$item[0]?></div>
                                </div>
                            </div>

                        <?php }
                        }
                        if(!$isAtLeastOne){
                            ?>
                            <div class="chheber-alert">
                                <div class="alert alert-warning">Aucun hébergement n'est disponible sur ces dates, désolé.</div>
                            </div>
                            <?php
                        }
                        ?>
                        </div>
                    </div>
                    <div data-lat="<?= $Ville->getLatitude()?>" data-lng="<?= $Ville->getLongitude()?>" data-zoom="12" class="map-hebergement" id="map"></div>
                </div>

                <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
                integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
                <script src="../assets/js/changeHebergement.js"></script>

            <?php

            } else { ?>
                <div class="alert alert-warning">Aucun hôtel n'est libre pour cette date</div>
                
            <?php }

        }

    } else { ?>
        <div class="alert alert-warning">Un problème est survenu</div>
    <?php }

} else { ?>
    <div class="alert alert-warning">Vous devez être connecté pour accéder à ce contenu</div>
<?php } ?>

<?php
require_once "footer.php";
?>