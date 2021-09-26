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
            <div id="hd-title"><a href="changeHebergement.php" class="btn btn-sm btn-secondary back-button"><</a><?= $Hebergement->getLibelle() ?></div>
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

            <div id="hd-price">
                <div class="hd-title">Détail du prix</div>
                <div><span id="nuits">0 nuit</span> * <span data-prix="<?=$Hebergement->getPrix()?>" id="prix"><?=$Hebergement->getPrix()?> €</span> = <span id="total">0 €</span></div>
            </div>
        </div>

        <div>
            <button id="submit" class="btn btn-success btn-sm">Valider</button>
        </div>
        <div id="hd-avis"></div>
    </div>

    <script src="../js/changeHebergementDescription.js"></script>
<?php
} else { ?>
    <div class="alert alert-warning">Un problème est survenu avec les paramètres</div>
<?php } ?>
<?php
require_once "footer.php";
?>