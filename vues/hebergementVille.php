<?php
require_once "header.php";

$Ville = new Ville($_GET["idVille"]);

// à ce niveau il faudra avoir récupérer les coordonnées de la carte région de france et donc déjà avoir choisi la région

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
<script src="../js/app.js"></script>

<?php
require_once "footer.php";
?>