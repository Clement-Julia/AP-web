<?php
require_once "header.php";
require_once "../Modeles/All.php";

$Hebergement = new Hebergement($_GET["idHebergement"]);
$month = new Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$lastmonday = $month->getStartingDay()->modify('last monday');

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
            <div>
                <!-- Faudra faire de l'ajax ici pour éviter le rechargement de la page en entier -->
            <p>
                <a href="hebergementDescription.php?idHebergement=<?=$_GET["idHebergement"]?>&month=<?= $month->prevMonth()->month;?>&year=<?= $month->prevMonth()->year;?>" class="btn btn-sm btn-primary">&lt</a>
                <?= $month->toString();?>
                <a href="hebergementDescription.php?idHebergement=<?=$_GET["idHebergement"]?>&month=<?= $month->nextMonth()->month;?>&year=<?= $month->nextMonth()->year;?>" class="btn btn-sm btn-primary">&gt</a>
            </p>
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