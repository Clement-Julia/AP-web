<?php
require_once "header.php";

$Lodging = new Region($_GET['idRegion']);
$Lodgings = $Lodging->getVilles();
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
                <a href="hebergementVille.php?idVille=<?= $item->getIdVille();?>" data-id="<?= $item->getIdVille()?>" data-name="<?= $item->getLibelle()?>" data-lat="<?= $item->getLatitude()?>" data-lng="<?= $item->getLongitude()?>" class="town-item js-marker">
                    <div class="town-picture"></div>
                    <div class="town-text"><?= $item->getLibelle()?></div>
                </a>
            <?php }
            ?>
        </div>
    </div>
    <div data-lat="<?=$Lodging->getLatitude();?>" data-lng="<?=$Lodging->getLongitude();?>" data-zoom="8" class="map" id="map"></div>
</div>



<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="../js/app.js"></script>

<?php
require_once "footer.php";
?>