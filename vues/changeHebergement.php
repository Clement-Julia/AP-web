<?php
require_once "header.php";

// (SECURITE) On vérifie que le paramètre récupéré est bien du type INT attendu
if (is_numeric($_SESSION['idReservationHebergement'])){

    
    $Reservation = new ReservationHebergement($_SESSION['idReservationHebergement']);
    
    
    if($Reservation->getIdUtilisateur() == $_SESSION['idUtilisateur']){
        
        $idVille = $Reservation->getIdVilleByHebergementId($Reservation->getIdHebergement());
        // Il faut vérifier et afficher uniquement les hôtels libres sur cet interval
        $freeHebergement = $Reservation->getFreeHebergement($idVille, $Reservation->getDateDebut(), $Reservation->getNbJours());
        
        if(!empty($freeHebergement)){

            $Ville = new Ville($idVille);

            ?>

            <div id="hv-container">
                <div id="choose-hebergement">
                    <div id="hv-back-button-container">
                        <a href="createTravel.php?idRegion=<?=$Ville->getIdRegion()?>" class="btn btn-sm btn-secondary back-button"><</a>
                    </div>
                    <?php
                    foreach ($freeHebergement as $item)
                    { ?>
                        <a data-hebergement="1" data-id="<?= $item->getIdHebergement()?>" data-name="<?= $item->getLibelle()?>" data-lat="<?= $item->getLatitude()?>" data-lng="<?= $item->getLongitude()?>" class="hebergement-item js-marker" href="changeHebergementDescription.php?idHebergement=<?=$item->getIdHebergement()?>">
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
            <script src="../js/changeHebergement.js"></script>

        <?php

        }

    }

} else { ?>
    <div class="alert alert-warning">Un problème est survenu</div>
<?php } ?>

<?php
require_once "footer.php";
?>