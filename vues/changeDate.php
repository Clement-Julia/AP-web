<?php
require_once "header.php";

if (is_numeric($_SESSION['idReservationHebergement'])){

    $Reservation = new ReservationHebergement($_SESSION['idReservationHebergement']);

    $Hebergement = new Hebergement($Reservation->getIdHebergement());

    $Month = new DateTime($Reservation->getDateDebut());
    $PreviousMonth = new DateTime($Month->format('Y-m-d') . '-1 month');
    $NextMonth = new DateTime($Month->format('Y-m-d') . '+1 month');

    $Calendar = new Month($Month->format('m'), $Month->format('y'));
    $PreviousCalendar = new Month($PreviousMonth->format('m'), $PreviousMonth->format('y'));
    $NextCalendar = new Month($NextMonth->format('m'), $NextMonth->format('y'));

    $lastmonday = $Calendar->getStartingDay()->modify('last monday');
    $previousLastmonday = $PreviousCalendar->getStartingDay()->modify('last monday');
    $nextLastmonday = $NextCalendar->getStartingDay()->modify('last monday');

    // Variante de la fonction, sans la var $date on récupère toutes les dates indisponibles de l'hôtel
    $bookingDates = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement());

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
                                    $date = (clone $lastmonday)->modify("+" . ($k + $i * 7) ." days") ?>
                                    <td class="
                                    ">
                                        <div class="
                                        <?=$Calendar->withinMonth($date) ? '' : 'calendar__overmonth';?> 
                                        <?= array_search($date->format('Y-m-d'), $bookingDates) != false ? 'booking' : 'grabCursor';?> 
                                        "><?= $date->format('d');?></div>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        </table>
                    </div>
                </div>




                <div>

                </div>




                <div>

                </div>



            </div>
            <div id="cd-resume-container">
                <!-- Le résumé avant / après  -->
                <div></div>
                <div></div>
            </div>
            <div id="cd-buttons-container">
                <!-- Les boutons -->
                <button class="btn btn-success mx-1">Validez</button>
                <button class="btn btn-secondary mx-1">Annuler</button>
            </div>
        </div>

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