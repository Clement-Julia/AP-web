<?php
require_once "header.php";
require_once "../Modeles/All.php";

$_GET["idVille"] = 13;

$Ville = new Ville($_GET["idVille"]);

// à ce niveau il faudra avoir récupérer les coordonnées de la carte région de france et donc déjà avoir choisi la région

?>

<div id="hv-container">
    <div id="choose-hebergement">
        <?php
        foreach ($Ville->getHebergements() as $item)
        { ?>
            <div data-name="<?= $item->getLibelle()?>" data-lat="<?= $item->getLatitude()?>" data-lng="<?= $item->getLongitude()?>" class="town-item js-marker">
                <div class="town-picture"></div>
                <div class="town-text"><?= $item->getDescription()?></div>
            </div>
        <?php }
        ?>
    </div>
    <div data-lat="47.2632836" data-lng="-0.3299687" data-zoom="8" class="map" id="map"></div>
</div>



<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="../js/app.js"></script>

<?php
require_once "footer.php";
?>