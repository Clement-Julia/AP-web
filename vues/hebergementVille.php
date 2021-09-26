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

    $ReservationVoyage = new ReservationVoyage();
    $BuildingRegionId = $ReservationVoyage->getIdRegionForBuildingTravelByUserId($_SESSION['idUtilisateur']) ? $ReservationVoyage->getIdRegionForBuildingTravelByUserId($_SESSION['idUtilisateur']) : $_SESSION['idRegion'];
    
    if($BuildingRegionId != null){
        $Ville = new Ville($_GET["idVille"]);
        $idRegion = $Ville->getIdRegion();

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
                        <a data-hebergement="1" data-id="<?= $item->getIdHebergement()?>" data-name="<?= $item->getLibelle()?>" data-lat="<?= $item->getLatitude()?>" data-lng="<?= $item->getLongitude()?>" class="hebergement-item js-marker" href="hebergementDescription.php?idHebergement=<?=$item->getIdHebergement()?>">
                            <div class="hebergement-picture"></div>
                            <div class="hebergement-text"><?= $item->getDescription()?></div>
                        </a>
                    <?php }
                    ?>
                </div>
                <div data-lat="<?= $Ville->getLatitude()?>" data-lng="<?= $Ville->getLongitude()?>" data-zoom="12" class="map-hebergement" id="map"></div>
            </div>



            <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
            <script src="../assets/js/app.js"></script>

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