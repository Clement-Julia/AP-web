<?php
require_once "header.php";

$Region = new Region();
$regions = $Region->getAllRegions();
?>

    <div id="map" class="map">
        <div id="map__image" class="map__image">

            <svg class="col-12" xmlns="http://www.w3.org/2000/svg" xmlns:amcharts="http://amcharts.com/ammap" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 625 600">
                <defs>
                    <style type="text/css">
                        .land
                        {
                            fill: #CCCCCC;
                            fill-opacity: 1;
                            stroke:white;
                            stroke-opacity: 1;
                            stroke-width:0.5;
                        }
                    </style>

                    <amcharts:ammap projection="mercator" leftLongitude="-4.778054" topLatitude="51.089278" rightLongitude="9.560176" bottomLatitude="41.363005"></amcharts:ammap>

                    <!-- All areas are listed in the line below. You can use this list in your script. -->
                    <!--{id:"FR-A"},{id:"FR-NAQ"},{id:"FR-ARA"},{id:"FR-BFC"},{id:"FR-BRE"},{id:"FR-CVL"},{id:"FR-COR"},{id:"FR-IDF"},{id:"FR-OCC"},{id:"FR-HDF"},{id:"FR-NOR"},{id:"FR-PDL"},{id:"FR-PAC"}-->
                </defs>
                    <path class="absent" title="Grand Est" d="<?=file_get_contents("../regionCarte/grand-Est.txt");?>"/>
                    <a data-idregion="<?=$regions['Nouvelle Aquitaine']?>" id="region-B" xlink:title="Nouvelle Aquitaine"><path class="present" d="<?=file_get_contents("../regionCarte/nouvelle-Aquitaine.txt");?>"/></a>
                    <path class="absent" title="Auvergne Rhône Alpes" d="<?=file_get_contents("../regionCarte/auvergne-Rhone-Alpes.txt");?>"/>
                    <path class="absent" title="Bourgogne-Franche-Comté" d="<?=file_get_contents("../regionCarte/bourgogne-Franche-Comte.txt");?>"/>
                    <a data-idregion="<?=$regions['Bretagne']?>" id="region-E" xlink:title="Bretagne"  ><path class="present" d="<?=file_get_contents("../regionCarte/bretagne.txt");?>"/></a>
                    <a data-idregion="<?=$regions['Centre Val de Loire']?>" id="region-F" xlink:title="Centre Val de Loire"  ><path class="present" d="<?=file_get_contents("../regionCarte/centre-Val-De-Loire.txt");?>"/></a>
                    <path class="absent" title="Corse" d="<?=file_get_contents("../regionCarte/corse.txt");?>"/>
                    <path class="absent" title="Île de France" d="<?=file_get_contents("../regionCarte/ile-De-France.txt");?>"/>
                    <path class="absent" title="Occitanie" d="<?=file_get_contents("../regionCarte/occitanie.txt");?>"/>
                    <path class="absent" title="Hauts de France" d="<?=file_get_contents("../regionCarte/hauts-De-France.txt");?>"/>
                    <path class="absent" d="<?=file_get_contents("../regionCarte/normandie.txt");?>"/>
                    <a data-idregion="<?=$regions['Pays de la Loire']?>" id="region-K" xlink:title="Pays de la Loire"  ><path class="present" title="Pays de la Loire" d="<?=file_get_contents("../regionCarte/pays-De-La-Loire.txt");?>"/></a>
                    <path class="absent" title="Provence Alpes Côte d'Azur" d="<?=file_get_contents("../regionCarte/provence-Alpes-Cote-D-Azur.txt");?>"/>
            </svg>

        </div>
        <div id="description-region">
            <div id="description-container">
                <div id="description">Cliquez sur une région pour en voir la description apparaître ...</div> 
                <div id="link-container" class="d-none">
                    <a id="link" href="" class="btn btn-sm btn-success region-a">Y aller</a>
                </div>
            </div>
        </div>
    </div>


<script src="../js/map.js"></script>

<?php
require_once "footer.php";
?>