<?php
require_once "header.php";

if (is_numeric($_SESSION['idReservationHebergement'])){

    $Reservation = new ReservationHebergement($_SESSION['idReservationHebergement']);

    $Hebergement = new Hebergement($Reservation->getIdHebergement());

    $dateDebut = new DateTime($Reservation->getDateDebut());
    $dateFin = new DateTime($Reservation->getDateFin());
    $PreviousMonth = new DateTime($dateDebut->format('Y-m-d') . '-1 month');
    $NextMonth = new DateTime($dateDebut->format('Y-m-d') . '+1 month');

    $Calendar = new Month($dateDebut->format('m'), $dateDebut->format('y'));
    $PreviousCalendar = new Month($PreviousMonth->format('m'), $PreviousMonth->format('y'));
    $NextCalendar = new Month($NextMonth->format('m'), $NextMonth->format('y'));

    $lastmonday = $Calendar->getStartingDay()->modify('last monday');
    $previousLastmonday = $PreviousCalendar->getStartingDay()->modify('last monday');
    $nextLastmonday = $NextCalendar->getStartingDay()->modify('last monday');

    // Variante de la fonction, sans la var $date on récupère toutes les dates indisponibles de l'hôtel
    $bookingDates = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement());

    print_r($bookingDates);

    if($Reservation->getIdUtilisateur() == $_SESSION['idUtilisateur']){

        // récupéré toutes les dates de cet hôtel (disponible, indisponible) et ça sur le mois en cours, celui d'avant et celui d'après
        // puis adapter la logique de selection sur les dates calendrier (click avant date debut -> on prolonge le voyage vers l'arrière), l'inverse à prévoir aussi.
        // ça serait bien d'avoir une vérification en JS rapido avec après en controleur la vraie vérification complexe avec l'arbre décisionnel

        ?>

        <div id="change-date-container">
            <div id="cd-header">
                <div id="cd-title">Bienvenu dans le modificateur de date KEVIN !</div>
            </div>
            <div id="cd-calendar-container">
                <!-- Les 3 calendriers -->


                <div>
                    <div class="calendar">
                        <?= $PreviousCalendar->toString();?>
                        <table data-nbjour="" data-date="" id="table1" class="calendar__table calendar__table--<?=$PreviousCalendar->getWeeks();?>weeks">
                            <tr>
                                <?php foreach($PreviousCalendar->days as $day){?>
                                    <th>
                                        <div><?=$day;?></div>
                                    </th>
                                <?php } ?>
                            </tr>
                        <?php for ($i = 0; $i < $PreviousCalendar->getWeeks(); $i++){ ?>
                            <tr>
                                <?php foreach($PreviousCalendar->days as $k => $day){
                                    $date = (clone $previousLastmonday)->modify("+" . ($k + $i * 7) ." days")
                                    ?>
                                    
                                    <td class="
                                    ">
                                        <div data-bool="False" id="<?=$date->format("Y-m-d")?>" class="
                                        <?=$PreviousCalendar->withinMonth($date) ? 'selectable' : 'calendar__overmonth';?> 
                                        <?=$date->format("Y-m-d") == $dateDebut->format("Y-m-d") && $PreviousCalendar->withinMonth($date) ? 'date-debut' : '';?> 
                                        <?=$date->format("Y-m-d") == $dateFin->format("Y-m-d") && $PreviousCalendar->withinMonth($date) ? 'date-fin' : '';?> 
                                        <?= in_array($date->format("Y-m-d"), $bookingDates) ? 'booking' : '';?>
                                        "><?= $date->format('d');?></div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </table>
                    </div>
                </div>

                <div>
                    <div class="calendar">
                        <?= $Calendar->toString();?>
                        <table data-nbjour="" data-date="" id="table1" class="calendar__table calendar__table--<?=$Calendar->getWeeks();?>weeks">
                            <tr>
                                <?php foreach($Calendar->days as $day){?>
                                    <th>
                                        <div><?=$day;?></div>
                                    </th>
                                <?php } ?>
                            </tr>
                        <?php for ($i = 0; $i < $Calendar->getWeeks(); $i++){ ?>
                            <tr>
                                <?php foreach($Calendar->days as $k => $day){
                                    $date = (clone $lastmonday)->modify("+" . ($k + $i * 7) ." days")
                                    ?>
                                    
                                    <td class="
                                    ">
                                        <div data-bool="False" id="<?=$date->format("Y-m-d")?>" class="
                                        <?=$Calendar->withinMonth($date) ? 'selectable' : 'calendar__overmonth';?> 
                                        <?=$date->format("Y-m-d") == $dateDebut->format("Y-m-d") && $Calendar->withinMonth($date) ? 'date-debut' : '';?> 
                                        <?=$date->format("Y-m-d") == $dateFin->format("Y-m-d") && $Calendar->withinMonth($date) ? 'date-fin' : '';?> 
                                        <?= in_array($date->format("Y-m-d"), $bookingDates) ? 'booking' : '';?>
                                        "><?= $date->format('d');?></div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </table>
                    </div>
                </div>




                <div>
                    <div class="calendar">
                        <?= $NextCalendar->toString();?>
                        <table data-nbjour="" data-date="" id="table1" class="calendar__table calendar__table--<?=$NextCalendar->getWeeks();?>weeks">
                            <tr>
                                <?php foreach($NextCalendar->days as $day){?>
                                    <th>
                                        <div><?=$day;?></div>
                                    </th>
                                <?php } ?>
                            </tr>
                        <?php for ($i = 0; $i < $NextCalendar->getWeeks(); $i++){ ?>
                            <tr>
                                <?php foreach($NextCalendar->days as $k => $day){
                                    $date = (clone $nextLastmonday)->modify("+" . ($k + $i * 7) ." days")
                                    ?>
                                    
                                    <td class="
                                    ">
                                        <div data-bool="False" id="<?=$date->format("Y-m-d")?>" class="
                                        <?=$NextCalendar->withinMonth($date) ? 'selectable' : 'calendar__overmonth';?> 
                                        <?=$date->format("Y-m-d") == $dateDebut->format("Y-m-d") && $NextCalendar->withinMonth($date) ? 'date-debut' : '';?> 
                                        <?=$date->format("Y-m-d") == $dateFin->format("Y-m-d") && $NextCalendar->withinMonth($date) ? 'date-fin' : '';?> 
                                        <?= in_array($date->format("Y-m-d"), $bookingDates) ? 'booking' : '';?>
                                        "><?= $date->format('d');?></div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </table>
                    </div>
                </div>

            </div>
            <div id="cd-resume-container">
                <!-- Le résumé avant / après  -->
                <div>
                    <div>Date d'arrivée : </div>
                    <div>Date de départ : </div>
                    <div>Nombre de jour : </div>
                    <div id="prixHebergement" data-prix="<?=$Hebergement->getPrix()?>" >Prix : </div>
                </div>
                <div>
                    <div>Date d'arrivée : <span id="d-start"></span></div>
                    <div>Date de départ : <span id="d-end"></span></div>
                    <div>Nombre de jour : <span id="nbJours"></span></div>
                    <div>Prix : <span id="prix"></span></div>
                </div>
            </div>
            <div id="cd-buttons-container">
                <!-- Les boutons -->
                <button class="btn btn-success mx-1">Valider</button>
                <button class="btn btn-secondary mx-1">Annuler</button>
            </div>
        </div>

        <script src="../js/changeDate.js"></script>
        <script src="../js/moment.js"></script>

        <?php
    } else { ?>
        <div class="alert alert-danger">Vous ne pouvez pas modifier un voyage qui ne vous appartient pas</div>
        
    <?php }

} else { ?>
    <div class="alert alert-warning">Un problème est survenu</div>
<?php } ?>

<?php
require_once "footer.php";
?>