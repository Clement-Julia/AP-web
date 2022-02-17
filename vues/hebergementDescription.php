<?php
require_once "header.php";

if(!empty($_SESSION['idUtilisateur'])){

    $avis = new Avis();
    $avis_by_date = $avis->getAvisByDate($_GET["idHebergement"]);
    $avis_asc = $avis->getAvisAsc($_GET["idHebergement"]);
    $avis_desc = $avis->getAvisDesc($_GET["idHebergement"]);

    // (SECURITE) On vérifie que le paramètre récupéré est bien du type INT attendu
    if (!empty($_GET['idHebergement']) && is_numeric($_GET['idHebergement'])){
        $doesItExist = new Hebergement($_GET["idHebergement"]);
        // (VERIFICATION) On vérifie que l'hébergement existe réellement
        if($doesItExist->getIdHebergement() != null){
            // (VERIFICATION) On vérifie que l'hébergement est bien présent dans la ville choisi
            if($doesItExist->getIdVille() == $_SESSION['idVille']){

                $ReservationVoyage = new ReservationVoyage();
                $isBuilding = $ReservationVoyage->getIsBuildingByUserId($_SESSION['idUtilisateur']);
                if (empty($isBuilding)){

                    $dateDebut = new DateTime($_SESSION['date']);
                    $NextMonth = new DateTime($dateDebut->format('Y-m-d') . '+1 month');

                } else {
                    
                    $lastReservation = $ReservationVoyage->getLastReservationHebergement($isBuilding['idReservationVoyage']);
                    $dateDebut = new DateTime($lastReservation['dateFin']);
                    $NextMonth = new DateTime($lastReservation['dateFin'] . '+1 month');
                }

                $_SESSION['idHebergement'] = $_GET['idHebergement'];

                $today = new DateTime();

                $Calendar = new Month($dateDebut->format('m'), $dateDebut->format('y'));
                $NextCalendar = new Month($NextMonth->format('m'), $NextMonth->format('y'));

                $lastmonday = $Calendar->getStartingDay()->format('N') === '1' ? $Calendar->getStartingDay() : $Calendar->getStartingDay()->modify('last monday');
                $nextLastmonday = $NextCalendar->getStartingDay()->format('N') === '1' ? $NextCalendar->getStartingDay() : $NextCalendar->getStartingDay()->modify('last monday');

                $Hebergement = new Hebergement($_GET["idHebergement"]);
                $bookingDates = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement(), $dateDebut->format('y-m-d'));

                $Favoris = new Favoris($_SESSION['idHebergement'], $_SESSION['idUtilisateur']);

                $Images = new Images($Hebergement->getUuid());

                $average = $avis->getAverageAvis($_GET["idHebergement"]);
                ?>

                <style>
                    body{
                        background-image: url('../assets/src/img/background/HebergementDes.jpg');
                        background-size: cover;
                        background-repeat: no-repeat;
                    }
                    .card-header{
                        color: black;
                    }
                    #navbar{
                        background-color: #27272773 !important;
                        backdrop-filter: blur(12px);
                    }
                </style>

                <div data-idHebergement="<?=$_GET["idHebergement"]?>" id="hebergement-description-container" class="form-container text-light mb-2">
                    <div id="hd-title-container">
                        <div id="hd-title"><a href="hebergementVille.php?idVille=<?=$Hebergement->getIdVille()?>" class="btn btn-sm btn-secondary back-button text-light"><</a><?= htmlspecialchars($Hebergement->getLibelle(), ENT_QUOTES) ?></div>
                        <div id="hd-infos">
                            <div id="hd-rate">
                                <?= ($average != 0) ? $average.'<i class="fas fa-star" style="color: #f2f200;"></i>' : "<span class='text-muted fst-italic'>Aucun avis n'a été publié...</span> "?>
                            </div>
                            <div id="hd-heart"><?=$Favoris->getIdHebergement() == null ? "<i class='far fa-heart'></i>" : "<i class='fas fa-heart'></i>"?></div>
                        </div>
                    </div>
                    <?=$Images->getImageDescriptionHebergementCode()?>
                    <div id="hd-description-container">
                        <div id="hd-description" class="card form-container text-light">
                            <div class="card-header text-light"><h6>Description</h6></div>
                            <div class="card-body"><?= htmlspecialchars($Hebergement->getDescription(), ENT_QUOTES) ?></div>
                        </div>
                            <div class="card form-container text-light">
                                <div class="card-header text-light"><h6>Ce que propose le logement : </h6></div>
                                <div class="card-body d-flex flex-wrap">
                            <?php
                            foreach ($Hebergement->getOptions() as $item){
                                ?>
                                    <div class="hd-tools-item"><i class="<?=$item->getIcon()?>"></i><span><?=$item->getLibelle()?></span></div>
                                <?php
                            }
                            ?>
                                </div>
                            </div>
                    </div>
                    <div id="hd-date-price-container">
                        <div id="hd-date" class="card form-container">
                            <div class="card-header text-light">
                                <h6>Nombre de nuit</h6>
                            </div>
                            <p class="text-light ms-2">Astuce de l'équipe : <span class="text-white-50">"Cliquer sur une date pour définir le nombre de nuit"</span></p>
                            <div id="calendar-container" class="card-body">
                                <div class="calendar background-calendar">
                                    <div class="calendar-header"><?= $Calendar->toString();?></div>
                                    <table id="table1" class="calendar__table calendar__table--<?=$Calendar->getWeeks();?>weeks">
                                        <tr>
                                            <?php foreach($Calendar->days as $day){?>
                                                <th>
                                                    <?=$day;?>
                                                </th>
                                            <?php } ?>
                                        </tr>
                                    <?php for ($i = 0; $i < $Calendar->getWeeks(); $i++){ ?>
                                        <tr>
                                            <?php foreach($Calendar->days as $k => $day){
                                                $date = (clone $lastmonday)->modify("+" . ($k + $i * 7) ." days") ?>
                                                <td>
                                                    <div id="<?=$date->format("Y-m-d")?>" class="
                                                    <?=$Calendar->withinMonth($date) ? '' : 'calendar__overmonth';?>
                                                    <?=$date->format("Y-m-d") == $dateDebut->format("Y-m-d") && $Calendar->withinMonth($date) ? 'date-debut' : '';?> 
                                                    <?= in_array($date->format("Y-m-d"), $bookingDates) ? 'booking' : '';?>
                                                    <?= !in_array($date->format("Y-m-d"), $bookingDates) &&  !($date->format("Y-m-d") < $dateDebut->format("Y-m-d")) && $Calendar->withinMonth($date) ? 'selectable' : '';?>
                                                    "><?= $date->format('d');?></div>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </table>
                                </div>

                                <div class="calendar background-calendar">
                                    <div class="calendar-header"><?= $NextCalendar->toString();?></div>
                                    <table id="table2" class="calendar__table calendar__table--<?=$NextCalendar->getWeeks();?>weeks">
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
                                                $date = (clone $nextLastmonday)->modify("+" . ($k + $i * 7) ." days") ?>
                                                <td>
                                                    <div id="<?=$date->format("Y-m-d")?>" class="
                                                    <?=$NextCalendar->withinMonth($date) ? '' : 'calendar__overmonth';?>
                                                    <?=$date->format("Y-m-d") == $dateDebut->format("Y-m-d") && $NextCalendar->withinMonth($date) ? 'date-debut' : '';?> 
                                                    <?= in_array($date->format("Y-m-d"), $bookingDates) ? 'booking' : '';?>
                                                    <?= !in_array($date->format("Y-m-d"), $bookingDates) &&  !($date->format("Y-m-d") < $dateDebut->format("Y-m-d")) && $NextCalendar->withinMonth($date) ? 'selectable' : '';?>
                                                    "><?= $date->format('d');?></div>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div id="hd-price" class="card form-container text-light">
                            <div class="card-header text-light"><h6>Détail du prix</h6></div>
                            <div class="card-body">
                                <span>Vous allez réserver : </span>
                                <span id="nbJours">0 nuit</span> à <span id="prixHebergement" data-prix="<?=$Hebergement->getPrix()?>"><?=$Hebergement->getPrix()?> €</span> <br> Montant total : <span id="prix">0 €</span>
                            </div>
                        </div>
                        <div>
                            <div id="alert-danger" class="alert alert-danger d-none"></div>
                        </div>
                    </div>
                    <?php if(!empty($_GET['error'])){ ?>
                        <div class="alert alert-warning">Les dates sélectionnées ne sont pas valide. Veuillez selectionner une plage de date libre.</div>
                    <?php } ?>

                    <div class="card form-container text-light">
                        <div class="card-header text-light">
                            <h6>Notes et avis</h6>
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="btn btn-outline-primary btn-radius me-1 active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Les plus récents</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="btn btn-outline-primary btn-radius me-1" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Positif</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="btn btn-outline-primary btn-radius me-1" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Négatif</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body form-container">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <?php
                                    $i = 0;
                                    $x = 0;
                                    foreach($avis_by_date as $avis){
                                        $name = "rating" . $i;
                                        ?>
                                        <div class="accordion-item form-containers">
                                            <h2 class="accordion-header" id="heading<?=$i?>">
                                                <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$i?>" aria-expanded="false" aria-controls="collapse<?=$i?>">
                                                    <?= $avis["nom"] . " " . $avis["prenom"] ?>
                                                    <div class="rating ms-5">
                                                        <input type="radio" name="<?=$name?>" value="5" id="<?=$x?>" <?= (5 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>

                                                        <input type="radio" name="<?=$name?>" value="4" id="<?=$x?>" <?= (4 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>

                                                        <?php $x++ ?>
                                                        <input type="radio" name="<?=$name?>" value="3" id="<?=$x?>"<?= (3 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>

                                                        <input type="radio" name="<?=$name?>" value="2" id="<?=$x?>"<?= (2 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>
                                                        
                                                        <input type="radio" name="<?=$name?>" value="1" id="<?=$x?>" <?= (1 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse<?=$i?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$i?>" data-bs-parent="#pills-home">
                                                <div class="accordion-body text-dark">
                                                    <?=  htmlspecialchars($avis["commentaire"], ENT_QUOTES) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                                    <?php
                                    foreach($avis_desc as $avis){
                                        $name = "rating" . $i;
                                        ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading<?=$i?>">
                                                <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$i?>" aria-expanded="false" aria-controls="collapse<?=$i?>">
                                                    <?= $avis["nom"] . " " . $avis["prenom"] ?>
                                                    <div class="rating ms-5">
                                                        <input type="radio" name="<?=$name?>" value="5" id="<?=$x?>" <?= (5 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>

                                                        <input type="radio" name="<?=$name?>" value="4" id="<?=$x?>" <?= (4 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>

                                                        <?php $x++ ?>
                                                        <input type="radio" name="<?=$name?>" value="3" id="<?=$x?>"<?= (3 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>

                                                        <input type="radio" name="<?=$name?>" value="2" id="<?=$x?>"<?= (2 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>
                                                        
                                                        <input type="radio" name="<?=$name?>" value="1" id="<?=$x?>" <?= (1 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse<?=$i?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$i?>" data-bs-parent="#pills-profile">
                                                <div class="accordion-body text-dark">
                                                    <?=  htmlspecialchars($avis["commentaire"], ENT_QUOTES) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </div>
                                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                                    <?php
                                    foreach($avis_asc as $avis){
                                        $name = "rating" . $i;
                                        ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading<?=$i?>">
                                                <button class="accordion-button collapsed text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$i?>" aria-expanded="false" aria-controls="collapse<?=$i?>">
                                                    <?= htmlspecialchars($avis["nom"], ENT_QUOTES) . " " . htmlspecialchars($avis["prenom"], ENT_QUOTES) ?>
                                                    <div class="rating ms-5">
                                                        <input type="radio" name="<?=$name?>" value="5" id="<?=$x?>" <?= (5 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>

                                                        <input type="radio" name="<?=$name?>" value="4" id="<?=$x?>" <?= (4 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>

                                                        <?php $x++ ?>
                                                        <input type="radio" name="<?=$name?>" value="3" id="<?=$x?>"<?= (3 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>

                                                        <input type="radio" name="<?=$name?>" value="2" id="<?=$x?>"<?= (2 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>
                                                        
                                                        <input type="radio" name="<?=$name?>" value="1" id="<?=$x?>" <?= (1 == $avis["note"]) ? "checked" : "" ?> disabled>
                                                        <label class="rating_size" for="<?=$x?>">☆</label>
                                                        <?php $x++ ?>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="collapse<?=$i?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$i?>" data-bs-parent="#pills-contact">
                                                <div class="accordion-body text-dark">
                                                    <?= htmlspecialchars($avis["commentaire"], ENT_QUOTES) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card form-container text-light">
                        <div class="card-body d-flex justify-content-center">
                            <button id="submit" class="btn btn-success btn-sm">Valider</button>
                            <div id="hidden" class="d-none">
                                <div>Souhaitez vous ajoutez une destination à votre voyage ?</div>
                                <div>
                                    <button id="submitYes" class="btn btn-sm btn-success">Oui</button>
                                    <button id="submitNo" class="btn btn-sm btn-secondary">Non</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="hd-avis"></div>
                </div>

                <script src="../assets/js/hebergementDescription.js"></script>
                <script src="../assets/js/moment.js"></script>
            <?php

            }
            else { ?>
                <div class="alert alert-warning">L'hébergement sélectionné ne correspond pas à la ville choisie</div>
            <?php }

        } else { ?>
            <div class="alert alert-warning">L'hébergement sélectionné n'existe pas</div>
        <?php }

    } else { ?>
        <div class="alert alert-warning">Un problème est survenu avec les paramètres</div>
    <?php }

} else { ?>
    <div class="alert alert-warning">Vous devez être connecté pour accéder à ce contenu</div>
<?php } ?>

<?php
require_once "footer.php";
?>