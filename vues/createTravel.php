<?php
require_once "header.php";
require_once "../Modeles/All.php";

$_GET["idRegion"] = 2;

$Lodging = new Region();
$Lodgings = $Lodging->getTownByRegionId($_GET["idRegion"]);
// à ce niveau il faudra avoir récupérer les coordonnées de la carte région de france et donc déjà avoir choisi la région

?>

<div id="create-travel-container">
    <div id="ct-choose-town">
        <div id="choose-town-top">
            Choisir une ville
        </div>
        <div id="choose-town-bot">
            <?php
            foreach ($Lodgings as $item)
            { ?>
                <div data-id="<?= $item["idVille"]?>" data-name="<?= $item["libelle"]?>" data-lat="<?= $item["latitude"]?>" data-lng="<?= $item["longitude"]?>" class="town-item js-marker">
                    <div class="town-picture"></div>
                    <div class="town-text"><?= $item["libelle"]?></div>
                </div>
            <?php }
            ?>
        </div>
    </div>
    <div data-lat="47.2632836" data-lng="-0.3299687" data-zoom="8" class="map" id="map"></div>
</div>



<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="../js/app.js"></script>

<?php
require_once "footer.php";
?>