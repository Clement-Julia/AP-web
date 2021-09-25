<?php
require_once "header.php";

// l'utilisateur à t-il un voyage ne cours
// si oui quel est la réservation hebergement la plus récente (max id)
// prendre la dernière date + nbjour
$ReservationVoyage = new ReservationVoyage();
$isBuilding = $ReservationVoyage->getIsBuildingByUserId($_SESSION['idUtilisateur']);
if (empty($isBuilding)){

    $_SESSION['date']['start_travel']['date_entiere'] = '2021-09-23';
    $actualDate = new DateTime($_SESSION['date']['start_travel']['date_entiere']);
    $nextDate = new DateTime($_SESSION['date']['start_travel']['date_entiere'] . "+1 months");

} else {
    
    $lastReservation = $ReservationVoyage->getLastReservationHebergement($isBuilding['idReservationVoyage']);
    print_r($lastReservation);
    $actualDate = new DateTime($lastReservation['dateFin']);
    $nextDate = new DateTime($lastReservation['dateFin'] . "+1 months");
}

$lastDayOfMonth = date('t', mktime(0, 0, 0, $actualDate->format('m'), 1, $actualDate->format('y')));

$Hebergement = new Hebergement($_GET["idHebergement"]);
$isBooking = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement(), $actualDate->format('y-m-d'));

$actualMonthCalendar = new Month($actualDate->format('m'), $actualDate->format('y'));
$lastmonday = $actualMonthCalendar->getStartingDay()->modify('last monday');
$nextMonthCalendar = new Month($nextDate->format('m'), $nextDate->format('y'));
$secondLastmonday = $nextMonthCalendar->getStartingDay()->modify('last monday');

?>

<div data-idHebergement="<?=$_GET["idHebergement"]?>" id="hebergement-description-container">
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
            foreach ($Hebergement->getOptions() as $item){
                ?>
                    <div class="hd-tools-item"><?=$item->getIcon()?><span><?=$item->getLibelle()?></span></div>
                <?php
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
                    <?= $actualMonthCalendar->toString();?>
                    <table data-nbjour="<?=$lastDayOfMonth?>" data-date="<?=$actualDate->format('d')?>" id="table1" class="calendar__table calendar__table--<?=$actualMonthCalendar->getWeeks();?>weeks">
                        <tr>
                            <?php foreach($actualMonthCalendar->days as $day){?>
                                <th>
                                    <div><?=$day;?></div>
                                </th>
                            <?php } ?>
                        </tr>
                    <?php for ($i = 0; $i < $actualMonthCalendar->getWeeks(); $i++){ ?>
                        <tr>
                            <?php foreach($actualMonthCalendar->days as $k => $day){
                                $date = (clone $lastmonday)->modify("+" . ($k + $i * 7) ." days") ?>
                                <td class="
                                <?=$date->format('Y-m-d') == $actualDate->format('Y-m-d') ? 'test' : '';?>  
                                ">
                                    <div class="
                                    <?=$actualMonthCalendar->withinMonth($date) ? '' : 'calendar__overmonth';?> 
                                    <?=$date->format('Y-m-d') < $actualDate->format('Y-m-d') ? 'standardCursor' : '';?>
                                    <?= array_search($date->format('Y-m-d'), $isBooking) != false ? 'booking' : 'grabCursor';?> 
                                    "><?= $date->format('d');?></div>
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
                    <?= $nextMonthCalendar->toString();?>
                    <table id="table2" class="calendar__table calendar__table--<?=$nextMonthCalendar->getWeeks();?>weeks">
                        <tr>
                            <?php foreach($nextMonthCalendar->days as $day){?>
                                <th>
                                    <div><?=$day;?></div>
                                </th>
                            <?php } ?>
                        </tr>
                    <?php for ($i = 0; $i < $nextMonthCalendar->getWeeks(); $i++){ ?>
                        <tr>
                            <?php foreach($nextMonthCalendar->days as $k => $day){
                                $date = (clone $secondLastmonday)->modify("+" . ($k + $i * 7) ." days") ?>
                                <td class="">
                                    <div class="
                                    <?=$nextMonthCalendar->withinMonth($date) ? '' : 'calendar__overmonth';?>
                                    <?= array_search($date->format('Y-m-d'), $isBooking) != false ? 'booking' : 'grabCursor';?>
                                    "><?= $date->format('d');?></div>
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
            <div><span id="nuits">0 nuit</span> * <span data-prix="<?=$Hebergement->getPrix()?>" id="prix"><?=$Hebergement->getPrix()?> €</span> = <span id="total">0 €</span></div>
        </div>
    </div>
    <?php if(!empty($_GET['error'])){ ?>
        <div class="alert alert-warning">Les dates sélectionnées ne sont pas valide. Veuillez selectionner une plage de date libre.</div>
    <?php } ?>
    <div>
        <button id="submit" class="btn btn-success btn-sm">Valider</button>
        <div id="hidden" class="d-none">
            <div>Souhaitez vous ajoutez une destination à votre voyage ?</div>
            <button id="submitYes" class="btn btn-sm btn-success">Oui</button>
            <button id="submitNo" class="btn btn-sm btn-secondary">Non</button>
        </div>
    </div>
    <div id="hd-avis"></div>
</div>

<script src="../js/hebergementDescription.js"></script>

<?php
require_once "footer.php";
?>