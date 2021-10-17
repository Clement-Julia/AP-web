<?php
require_once "header.php";
if(!empty($_SESSION['idUtilisateur'])){
// (SECURITE) On vérifie que le paramètre récupéré est bien du type INT attendu
    if (is_numeric($_GET['idVille'])){

        // (SECURITE) S'il existe une variable SESSION idRegion, on vérifie le type INT
        if (!empty($_SESSION['idRegion']) && !is_numeric($_SESSION['idRegion'])){
            ?>
                <div class="alert alert-warning">Un problème est survenu avec le choix de la région</div>
            <?php   
        }

        // On récupère l'idRegion du voyage qu'on est en train de construire afin de le comparer à celui de la ville si l'utilisateur modifie cette valeur manuellement dans l'URL (pour ne pas réserver dans une ville d'une autre région)
        $ReservationVoyage = new ReservationVoyage();
        $BuildingRegionId = $ReservationVoyage->getIdRegionForBuildingTravelByUserId($_SESSION['idUtilisateur']) ? $ReservationVoyage->getIdRegionForBuildingTravelByUserId($_SESSION['idUtilisateur']) : $_SESSION['idRegion'];

        // On récupère la date de début de la prochaine partie du voyage pour afficher les hébergements disponible au moins pour 1 nuit
        $isBuilding = $ReservationVoyage->getIsBuildingByUserId($_SESSION['idUtilisateur']);
        if (empty($isBuilding)){
            $dateDebut = new DateTime($_SESSION['date']);
        } else {
            $lastReservation = $ReservationVoyage->getLastReservationHebergement($isBuilding['idReservationVoyage']);
            $dateDebut = new DateTime($lastReservation['dateFin']);
        }

        

        
        if($BuildingRegionId != null){

            $Ville = new Ville($_GET["idVille"]);
            $idRegion = $Ville->getIdRegion();
            $Hebergs = $Ville->getFreeHebergement($dateDebut, $Ville->getIdVille());

            // Lorsqu'on modifie manuellement l'idVille dans l'url, si la ville choisi ne correspond pas à la région -> erreur
            if ($BuildingRegionId == $idRegion){

                // Pour la vérification lors de la page suivante
                $_SESSION['idVille'] = $_GET["idVille"];

                ?>

                <div id="hv-container">
                    <div id="hv-back-button-container">
                        <a href="createTravel.php?idRegion=<?=$Ville->getIdRegion()?>" class="btn btn-sm btn-secondary back-button"><</a>
                    </div>
                    <div id="choose-hebergement">
                        <div class="row d-flex">
                        <?php
                        foreach ($Hebergs as $item)
                        { 
                            $HebergementTemp = new Hebergement($item[1]->getIdHebergement());
                            $Image = new Images($HebergementTemp->getUuid());
                            if($item[0] != "indisponible" && $item[0] != "disponible 0 nuit"){?>

                            <!-- <div class="col-xs-12 col-sm-12 col-md-6 mb-3 col-xl-4"> -->
                            <div class="col-xs-12 col-sm-12 col-md-6 col-xl-6 col-xxl-4 d-flex align-items-stretch flex-wrap">
                                <div id="<?= $item[1]->getIdHebergement()?>" data-hebergement="1" data-id="<?= $item[1]->getIdHebergement()?>" data-name="<?= $item[1]->getLibelle()?>" data-lat="<?= $item[1]->getLatitude()?>" data-lng="<?= $item[1]->getLongitude()?>" data-zoom="12" data-price="<?=$item[1]->getPrix()?>" class="card ct-a js-marker">
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
                        ?>
                        </div>
                    </div>
                    <div data-lat="<?= $Ville->getLatitude()?>" data-lng="<?= $Ville->getLongitude()?>" data-zoom="12" class="map-hebergement" id="map"></div>
                </div>



                <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
                integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
                <script src="../assets/js/hebergementVille.js"></script>

            <?php
            } else { 
                ?>
                <div class="alert alert-warning">Un problème est survenu avec le choix de la ville</div>
                <?php   
            }

        }

    } else { ?>
        <div class="alert alert-warning">Un problème est survenu avec les paramètres</div>
    <?php } 

} else { ?>
    <div class="alert alert-warning">Vous devez être connecté pour accéder à ce contenu</div>
<?php } ?>


<?php
require_once "footer.php";
?>