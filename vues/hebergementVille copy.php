<?php
require_once "header.php";

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

            ?>

            <div id="hv-container">
                <div id="choose-hebergement">
                    <div id="hv-back-button-container">
                        <a href="createTravel.php?idRegion=<?=$Ville->getIdRegion()?>" class="btn btn-sm btn-secondary back-button"><</a>
                    </div>
                    <?php
                    foreach ($Ville->getHebergements() as $item)
                    { ?>

                    <div class="col-md-4 mb-3 col-lg-3">
                        <div data-hebergement="1" data-id="<?= $item->getIdHebergement()?>" data-name="<?= $item->getLibelle()?>" data-lat="<?= $item->getLatitude()?>" data-lng="<?= $item->getLongitude()?>" data-zoom="12" class="card ct-a js-marker">
                            <img class="img-fluid" alt="100%x280" src="https://images.unsplash.com/photo-1530735606451-8f5f13955328?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1470&q=80">
                            <div class="card-body hv-text-hebergement">
                                <h6 class="card-title"><?= $item->getLibelle()?></h6>
                                <p><?= $item->getDescription()?></p>
                            </div>
                            <div class="card-footer text-muted">Disponible X jour (fct à venir)</div>
                        </div>
                    </div>

                    <?php }
                    ?>
                </div>
                <div data-lat="<?= $Ville->getLatitude()?>" data-lng="<?= $Ville->getLongitude()?>" data-zoom="12" class="map-hebergement" id="map"></div>
            </div>



            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
            <script src="../js/app.js"></script>

        <?php
        } else { 
            ?>
            <div class="alert alert-warning">Un problème est survenu avec le choix de la ville</div>
            <?php   
        }

    }

} else { ?>
    <div class="alert alert-warning">Un problème est survenu avec les paramètres</div>
<?php } ?>

<?php
require_once "footer.php";
?>