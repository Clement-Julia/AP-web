<?php
require_once "header.php";

// (SECURITE) On vérifie que le paramètre récupéré est bien du type INT attendu
if ((!empty($_GET['idRegion']) && is_numeric($_GET['idRegion'])) || !isset($_GET['idRegion'])){

    if (!empty($_GET['idRegion'])){
        $_SESSION['idRegion'] = $_GET['idRegion'];
    }

    $ReservationVoyage = new ReservationVoyage();
    $BuildingTravelId = $ReservationVoyage->getIdBuildingTravelByUserId($_SESSION['idUtilisateur']);
    if($BuildingTravelId != null){
        $ReservationVoyage = new ReservationVoyage($BuildingTravelId);
    }

    // On récupère l'idRegion soit par le GET si l'utilisateur est en train de renseigner le début de son voyage (pas encore inscrit en BDD) ou bien alors par la BDD
    $idRegion = isset($_GET['idRegion']) ? $_GET['idRegion'] : $ReservationVoyage->getIdRegionForBuildingTravelByUserId($_SESSION['idUtilisateur']);

    $Lodging = new Region($idRegion);
    $Lodgings = $Lodging->getVilles();

    $ReservationHebergement = new ReservationHebergement($_SESSION['idReservationHebergement']);
    $idVilleToChange = $ReservationHebergement->getIdVilleByHebergementId($ReservationHebergement->getIdHebergement());

    foreach($Lodgings as $key => $value){
        if($value->getIdVille() == $idVilleToChange){
            unset($Lodgings[$key]);
        }
    }

    ?>
    <div id="ligne-points">
    <?php
    // On récupère les lat et lng des reservations hébergement fait par l'utilisateur sur un voyage en cours de construction
    foreach ($ReservationVoyage->getVilleLatLngByUserId($_SESSION['idUtilisateur']) as $latLngVille){ ?>
        <div data-lat="<?=$latLngVille['latitude']?>" data-lng="<?=$latLngVille['longitude']?>"></div>
    <?php } ?>
    </div>

    <div id="change-create-travel-container">
        <div id="hv-back-button-container">
            <a href="createTravel.php?idRegion=<?=$idRegion?>" class="btn btn-sm btn-secondary back-button"><</a>
        </div>
        
        <div id="change-choose-town-bot">
            <?php
            foreach ($Lodgings as $item)
            { ?>
                <div class="col-md-6 mb-3 col-lg-3">
                    <div data-id="<?= $item->getIdVille()?>" data-name="<?= $item->getLibelle()?>" data-lat="<?= $item->getLatitude()?>" data-lng="<?= $item->getLongitude()?>" data-zoom="9" class="card ct-a js-marker">
                        <img class="img-fluid" alt="100%x280" src="https://images.unsplash.com/photo-1530735606451-8f5f13955328?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1470&q=80">
                        <div class="card-body ct-text-ville">
                            <h6 class="card-title"><?= $item->getLibelle()?></h6>
                        </div>
                    </div>
                </div>
            <?php }
            ?>
        </div>

        <div data-lat="<?=$Lodging->getLatitude();?>" data-lng="<?=$Lodging->getLongitude();?>" data-zoom="8" class="map" id="map"></div>
    </div>



    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
    integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
    <script src="../js/changeVille.js"></script>

<?php } else { ?>
    <div class="alert alert-warning">Un problème est survenu avec les paramètres</div>
<?php } ?>

<?php
require_once "footer.php";
?>