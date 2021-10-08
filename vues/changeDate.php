<?php
require_once "header.php";

if (is_numeric($_SESSION['idReservationHebergement'])){

    $Reservation = new ReservationHebergement($_SESSION['idReservationHebergement']);

    $Hebergement = new Hebergement($Reservation->getIdHebergement());

    $today = new DateTime();
    $dateDebut = new DateTime($Reservation->getDateDebut());
    $dateFin = new DateTime($Reservation->getDateFin());
    $PreviousMonth = new DateTime($dateDebut->format('Y-m-d') . '-1 month');
    $NextMonth = new DateTime($dateDebut->format('Y-m-d') . '+1 month');

    $Calendar = new Month($dateDebut->format('m'), $dateDebut->format('y'));
    $PreviousCalendar = new Month($PreviousMonth->format('m'), $PreviousMonth->format('y'));
    $NextCalendar = new Month($NextMonth->format('m'), $NextMonth->format('y'));

    $lastmonday = $Calendar->getStartingDay()->format('N') === '1' ? $Calendar->getStartingDay() : $Calendar->getStartingDay()->modify('last monday');

    $previousLastmonday = $PreviousCalendar->getStartingDay()->format('N') === '1' ? $PreviousCalendar->getStartingDay() : $PreviousCalendar->getStartingDay()->modify('last monday');

    $nextLastmonday = $NextCalendar->getStartingDay()->format('N') === '1' ? $NextCalendar->getStartingDay() : $NextCalendar->getStartingDay()->modify('last monday');

    // Variante de la fonction, sans la var $date on récupère toutes les dates indisponibles de l'hôtel
    $bookingDates = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement());

    if($Reservation->getIdUtilisateur() == $_SESSION['idUtilisateur']){

        // récupéré toutes les dates de cet hôtel (disponible, indisponible) et ça sur le mois en cours, celui d'avant et celui d'après
        // puis adapter la logique de selection sur les dates calendrier (click avant date debut -> on prolonge le voyage vers l'arrière), l'inverse à prévoir aussi.
        // ça serait bien d'avoir une vérification en JS rapido avec après en controleur la vraie vérification complexe avec l'arbre décisionnel

        ?>

        <div id="change-date-container">
            <div class="card">
                <div class="card-header text-center"><h6>Modification des dates du séjour</h6></div>
            </div>
            <div id="cd-calendar-container" class="card">
                <!-- Les 3 calendriers -->


                <div id="cd-calendar" class="card">
                    <div class="calendar">
                        <div class="calendar-header"><?= $PreviousCalendar->toString();?></div>
                        
                        <table id="table1" class="calendar__table calendar__table--<?=$PreviousCalendar->getWeeks();?>weeks">
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
                                        <?=$PreviousCalendar->withinMonth($date) ? '' : 'calendar__overmonth';?> 
                                        <?=$date->format("Y-m-d") == $dateDebut->format("Y-m-d") && $PreviousCalendar->withinMonth($date) ? 'date-debut' : '';?> 
                                        <?=$date->format("Y-m-d") == $dateFin->format("Y-m-d") && $PreviousCalendar->withinMonth($date) ? 'date-fin' : '';?> 
                                        <?= in_array($date->format("Y-m-d"), $bookingDates) ? 'booking' : '';?>
                                        <?= !in_array($date->format("Y-m-d"), $bookingDates) &&  !($date->format("Y-m-d") < $today->format("Y-m-d")) ? 'selectable' : '';?>
                                        "><?= $date->format('d');?></div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </table>
                    </div>
                </div>

                <div>
                    <div class="calendar card">
                        <div class="calendar-header"><?= $Calendar->toString();?></div> 
                        <table id="table1" class="calendar__table calendar__table--<?=$Calendar->getWeeks();?>weeks">
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
                                        <?=$Calendar->withinMonth($date) ? '' : 'calendar__overmonth';?> 
                                        <?=$date->format("Y-m-d") == $dateDebut->format("Y-m-d") && $Calendar->withinMonth($date) ? 'date-debut' : '';?> 
                                        <?=$date->format("Y-m-d") == $dateFin->format("Y-m-d") && $Calendar->withinMonth($date) ? 'date-fin' : '';?> 
                                        <?= in_array($date->format("Y-m-d"), $bookingDates) ? 'booking' : '';?>
                                        <?= !in_array($date->format("Y-m-d"), $bookingDates) &&  !($date->format("Y-m-d") < $today->format("Y-m-d")) ? 'selectable' : '';?>
                                        "><?= $date->format('d');?></div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </table>
                    </div>
                </div>




                <div>
                    <div class="calendar card">
                        <div class="calendar-header"><?= $NextCalendar->toString();?></div> 
                        <table id="table1" class="calendar__table calendar__table--<?=$NextCalendar->getWeeks();?>weeks">
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
                                        <?=$NextCalendar->withinMonth($date) ? '' : 'calendar__overmonth';?>
                                        <?=$date->format("Y-m-d") == $dateDebut->format("Y-m-d") && $NextCalendar->withinMonth($date) ? 'date-debut' : '';?> 
                                        <?=$date->format("Y-m-d") == $dateFin->format("Y-m-d") && $NextCalendar->withinMonth($date) ? 'date-fin' : '';?> 
                                        <?= in_array($date->format("Y-m-d"), $bookingDates) ? 'booking' : '';?>
                                        <?= !in_array($date->format("Y-m-d"), $bookingDates) &&  !($date->format("Y-m-d") < $today->format("Y-m-d")) ? 'selectable' : '';?>
                                        "><?= $date->format('d');?></div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </table>
                    </div>
                </div>

            </div>
            <div id="cd-resume-container" class="card">
                <!-- Le résumé avant / après  -->
                <div class="card modification">
                    <div class="card-header">
                        <h6>Avant modification :</h6>
                    </div>
                    <div class="card-body">
                        <div>Date d'arrivée : <span class="float-right"><?=$Reservation->getDateDebut()?></span></div>
                        <div>Date de départ : <span class="float-right"><?=$Reservation->getDateFin()?></span></div>
                        <div>Nombre de jour : <span class="float-right"><?=$Reservation->getNbJours()?></span></div>
                        <div id="prixHebergement" data-prix="<?=$Hebergement->getPrix()?>" >Prix : <span class="float-right"><?=$Reservation->getPrix()?> €</span></div>
                    </div>
                </div>

                <div class="card modification">
                    <div class="card-header">
                        <h6>Après modification :</h6>
                    </div>
                    <div class="card-body">
                        <div>Date d'arrivée : <span class="float-right" id="d-start"> -</span></div>
                        <div>Date de départ : <span class="float-right" id="d-end"> -</span></div>
                        <div>Nombre de jour : <span class="float-right" id="nbJours"> -</span></div>
                        <div>Prix : <span class="float-right" id="prix"> -</span></div>
                    </div>
                </div>
            </div>
            <div>
                <div id="alert-warning" class="alert alert-warning d-none"></div>
                <div id="alert-danger" class="alert alert-danger d-none"></div>
            </div>
            <div id="cd-buttons-container" class="card">
                <!-- Les boutons -->
                <form action="../controleurs/changeDate.php" method="POST">
                    <div class="card-body text-center">
                        <input id="inputDateDebut" name="dateDebut" value="" type="hidden" >
                        <input id="inputDateFin" name="dateFin" value="" type="hidden" >
                        <button type="submit" class="btn btn-success mx-1">Valider</button>
                        <a href="createTravel.php" class="btn btn-secondary mx-1">Annuler</a>
                    </div>
                </form>
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