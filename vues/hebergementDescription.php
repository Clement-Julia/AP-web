<?php
require_once "header.php";
require_once "../Modeles/All.php";

$Hebergement = new Hebergement($_GET["idHebergement"]);
$month = new Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$lastmonday = $month->getStartingDay()->modify('last monday');

$monthPlusOne = new Month((intval(date('m')) + 1), intval(date('Y')));
$secondLastmonday = $monthPlusOne->getStartingDay()->modify('last monday');

?>

<div id="hebergement-description-container">
    <div id="hd-title-container">
        <div id="hd-title"><a href="hebergementVille.php?idVille=<?=$Hebergement->getIdVille()?>" class="btn btn-sm btn-secondary back-button"><</a><?= $Hebergement->getLibelle() ?></div>
        <div id="hd-infos">
            <div id="hd-rate"></div>
            <div id="hd-heart">"<3"</div>
        </div>
    </div>
    <div id="hd-pictures"></div>
    <div id="hd-description-container">
        <div id="hd-description"><?= $Hebergement->getDescription() ?></div>
        <div id="hd-tools">
            <div class="hd-title">Ce que propose le logement :</div>
            <div id="hd-tools-item-container">
            <?php
            foreach ($Hebergement->getOptions() as $key => $item){
                if ($item['option'] == 1){ ?>
                    <div class="hd-tools-item"><?=$item['icon']?><span><?=$key?></span></div>
                <?php }
            }
            ?>
            </div>
        </div>
    </div>
    <div id="hd-date-price-container">
        <div id="hd-date">
            <div class="hd-title">Calendrier</div>
            <div id="calendar-container">
                <div class="calendar">
                    <?= $month->toString();?>
                    <table class="calendar__table calendar__table--<?=$month->getWeeks();?>weeks">
                        <tr>
                            <?php foreach($month->days as $day){?>
                                <th>
                                    <div><?=$day;?></div>
                                </th>
                            <?php } ?>
                        </tr>
                    <?php for ($i = 0; $i < $month->getWeeks(); $i++){ ?>
                        <tr>
                            <?php foreach($month->days as $k => $day){
                                $date = (clone $lastmonday)->modify("+" . ($k + $i * 7) ." days") ?>
                                <td class="<?=$month->withinMonth($date) ? '' : 'calendar__overmonth';?>">
                                    <div><?= $date->format('d');?></div>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </table>
                </div>
    <!-- L'idée est d'avoir 2 mois pour pouvoir être sur d'obtenir une selection valide de l'utilisateur 

    On va avoir la date de départ qui sera fixe et stocké dans une variable depuis le début. Puis avec le click utilisateur, on sera combien de jour (avec une fonction) il aura choisi.
    -->
                <div class="calendar">
                    <?= $monthPlusOne->toString();?>
                    <table class="calendar__table calendar__table--<?=$monthPlusOne->getWeeks();?>weeks">
                        <tr>
                            <?php foreach($monthPlusOne->days as $day){?>
                                <th>
                                    <div><?=$day;?></div>
                                </th>
                            <?php } ?>
                        </tr>
                    <?php for ($i = 0; $i < $monthPlusOne->getWeeks(); $i++){ ?>
                        <tr>
                            <?php foreach($monthPlusOne->days as $k => $day){
                                $date = (clone $secondLastmonday)->modify("+" . ($k + $i * 7) ." days") ?>
                                <td class="<?=$monthPlusOne->withinMonth($date) ? '' : 'calendar__overmonth';?>">
                                    <div><?= $date->format('d');?></div>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </table>
                </div>

            </div>









        </div>
        <div id="hd-price">
            <div class="hd-title">Détail du prix</div>
            <div>Nombre de nuits * le prix = au montant à payer</div>
        </div>
    </div>
    <div id="hd-avis"></div>
</div>



<?php
require_once "footer.php";
?>