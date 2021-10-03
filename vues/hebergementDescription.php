<?php
require_once "header.php";

// (SECURITE) On vérifie que le paramètre récupéré est bien du type INT attendu
if (is_numeric($_GET['idHebergement'])){

    $ReservationVoyage = new ReservationVoyage();
    $isBuilding = $ReservationVoyage->getIsBuildingByUserId($_SESSION['idUtilisateur']);
    if (empty($isBuilding)){

        $actualDate = new DateTime($_SESSION['date']);
        $nextDate = new DateTime($_SESSION['date'] . "+1 months");

    } else {
        
        $lastReservation = $ReservationVoyage->getLastReservationHebergement($isBuilding['idReservationVoyage']);
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
                <div class="card">
                    <div class="card-header"><h6>Ce que propose le logement : </h6></div>
                    <div class="card-body d-flex flex-wrap">
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




        <!-- <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <blockquote class="blockquote mb-0">
                <p>A well-known quote, contained in a blockquote element.</p>
                <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                </blockquote>
            </div>
        </div> -->





        <div id="hd-date-price-container">
            <div id="hd-date" class="card">
                <div class="card-header"><h6>Calendrier</h6></div>
                <div id="calendar-container" class="card-body">
                    <div class="calendar">
                        <div class="calendar-header"><?= $actualMonthCalendar->toString();?></div>
                        <table data-nbjour="<?=$lastDayOfMonth?>" data-date="<?=$actualDate->format('d')?>" id="table1" class="calendar__table calendar__table--<?=$actualMonthCalendar->getWeeks();?>weeks">
                            <tr>
                                <?php foreach($actualMonthCalendar->days as $day){?>
                                    <th>
                                        <?=$day;?>
                                    </th>
                                <?php } ?>
                            </tr>
                        <?php for ($i = 0; $i < $actualMonthCalendar->getWeeks(); $i++){ ?>
                            <tr>
                                <?php foreach($actualMonthCalendar->days as $k => $day){
                                    $date = (clone $lastmonday)->modify("+" . ($k + $i * 7) ." days") ?>
                                    <td class="
                                    <?=$date->format('Y-m-d') == $actualDate->format('Y-m-d') ? 'dateDebut' : '';?>  
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
                        <div class="calendar-header"><?= $nextMonthCalendar->toString();?></div>
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
} else { ?>
    <div class="alert alert-warning">Un problème est survenu avec les paramètres</div>
<?php } ?>
<?php
require_once "footer.php";
?>