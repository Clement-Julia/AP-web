<?php
require_once "header.php";

if(!empty($_SESSION['idUtilisateur'])){
    $avis = new Avis();
    $avis_by_date = $avis->getAvisByDate($_GET["idHebergement"]);
    $avis_asc = $avis->getAvisAsc($_GET["idHebergement"]);
    $avis_desc = $avis->getAvisDesc($_GET["idHebergement"]);
    // (SECURITE) On vérifie que le paramètre récupéré est bien du type INT attendu
    if (is_numeric($_GET['idHebergement'])){

        // Comme le GET['idHebergement'] est accessible, on vérifi que l'hôtel choisi est disponible dans la région du voyage de l'utilisateur (afin de prévenir d'un changement manuelle de l'id par l'utilisateur)
        $Reservation = new ReservationHebergement($_SESSION['idReservationHebergement']);
        $idRegionReservation = $Reservation->getIdRegionByHebergementId($Reservation->getIdHebergement());
        $Hebergement = new Hebergement($_GET["idHebergement"]);
        $OldHebergement = new Hebergement($Reservation->getIdHebergement());
        if (!$Hebergement->getIdHebergement() == null){
            $idRegionHebergement = $Hebergement->getIdRegionByIdHebergement($Hebergement->getIdHebergement());
        }

        // Si l'hôtel est bien dans la bonne région ...
        if(!empty($idRegionHebergement) && $idRegionReservation === $idRegionHebergement){

            // On vérifie que l'hôtel est réellement disponible
            $dateDebut = new DateTime($Reservation->getDateDebut());
            $dayWhenBooking = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement(), $dateDebut->format('Y-m-d'));
            $boolean = false;
            for($i = 0; $i < $Reservation->getNbJours(); $i++){
                $dateTemp = new DateTime($Reservation->getDateDebut() . "+" . $i . "days");
                if(in_array($dateTemp->format('Y-m-d'), $dayWhenBooking)){
                    $boolean = true;
                }
            }

            // Si l'hôtel est bien disponible ...
            if (!$boolean){
                // On insert une variable session afin de pouvoir comparer à la page de traitement suivant, si la value du bouton submit n'a pas été modifier (sinon on renvoi à cette page avec l'idHebergement de la variable SESSION), cela permet d'éviter de refaire les mêmes vérifications deux fois
                $_SESSION['changeIdHebergement'] = $_GET['idHebergement'];
                // On inréinitialise la variable session pour que le bouton favoris fonctionne (il fonctionne avec cette variable session)
                $_SESSION['idHebergement'] = $_GET['idHebergement'];
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
                <div data-idHebergement="<?=$_GET["idHebergement"]?>" id="hebergement-description-container">
                    <div id="hd-title-container">
                        <div id="hd-title"><a href="changeHebergement.php?idVille=<?=$Hebergement->getIdVille()?>" class="btn btn-sm btn-secondary back-button text-light"><</a><?= $Hebergement->getLibelle() ?></div>
                        <div id="hd-infos">
                            <div id="hd-rate">
                                <?= ($average != 0) ? $average.'<i class="fas fa-star" style="color: #f2f200;"></i>' : "<span class='text-muted fst-italic'>Aucun avis n'a été publié...</span> "?>
                            </div>
                            <div id="hd-heart"><?=$Favoris->getIdHebergement() == null ? "<i class='far fa-heart'></i>" : "<i class='fas fa-heart'></i>"?></div>
                        </div>
                    </div>
                    <?=$Images->getImageDescriptionHebergementCode()?>
                    <div id="hd-description-container">
                        <div id="hd-description" class="card">
                            <div class="card-header"><h6>Description</h6></div>
                            <div class="card-body"><?= $Hebergement->getDescription() ?></div>
                            
                        </div>
                            <div class="card">
                                <div class="card-header"><h6>Ce que propose le logement : </h6></div>
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

                        <div id="hd-price" class="card">
                            <div class="card-header"><h6>Détail du prix</h6></div>
                            <div class="card-body">
                                <span id="nuits"><?=$Reservation->getNbJours()?> <?=$Reservation->getNbJours() > 1 ? 'nuits' : 'nuit'?></span> à <span data-prix="<?=$Hebergement->getPrix()?>" id="prix"><?=$Hebergement->getPrix()?> €</span> <br> Montant total : <span id="total"><?=$Hebergement->getPrix() * $Reservation->getNbJours()?> €</span>
                                <div class="card-text">
                                    <?php if($Reservation->getPrix() <= $Hebergement->getPrix() * $Reservation->getNbJours()){ ?>
                                    <div>Réserver cet hébergement à la place de "<?=$OldHebergement->getLibelle()?>" vous coûtera <?=($Hebergement->getPrix() * $Reservation->getNbJours() - $Reservation->getPrix())?>€ de plus</div>
                                    <?php } else { ?>
                                        <br>
                                        <div>Réserver cet hébergement à la place de "<?=$OldHebergement->getLibelle()?>" vous fera économiser <?=$Reservation->getPrix() - ($Hebergement->getPrix() * $Reservation->getNbJours())?>€</div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
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
                        <div class="card-body">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                    <?php
                                    $i = 0;
                                    $x = 0;
                                    foreach($avis_by_date as $avis){
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
                                            <div id="collapse<?=$i?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$i?>" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <?= $avis["commentaire"] ?>
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
                                            <div id="collapse<?=$i?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$i?>" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <?= $avis["commentaire"] ?>
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
                                            <div id="collapse<?=$i?>" class="accordion-collapse collapse" aria-labelledby="heading<?=$i?>" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
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

                    <form action="../controleurs/changeHebergement.php" method="POST">
                        <div class="card text-center">
                            <div class="card-body">
                                <input type="hidden" name="idHebergement" value="<?=$_GET['idHebergement'];?>">
                                <button id="submit" class="btn btn-success btn-sm">Changer pour cet hôtel</button>
                            </div>
                        </div>
                    </form>
                    <div id="hd-avis"></div>
                </div>

                <script src="../assets/js/changeHebergementDescription.js"></script>
            <?php

            } else { ?>
                <div class="alert alert-warning">L'hôtel choisi n'est pas disponible pour la période souhaité</div>
        <?php }
            
        } else {?>
            <div class="alert alert-warning">Un problème est survenu, l'hebergement choisi n'est pas disponible dans la région de votre voyage ou n'existe simplement pas</div>
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