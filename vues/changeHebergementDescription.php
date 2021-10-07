<?php
require_once "header.php";

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
                ?>

            <div data-idHebergement="<?=$_GET["idHebergement"]?>" id="hebergement-description-container">
                <div id="hd-title-container">
                    <div id="hd-title"><a href="changeHebergement.php?idVille=<?=$Hebergement->getIdVille()?>" class="btn btn-sm btn-secondary back-button"><</a><?= $Hebergement->getLibelle() ?></div>
                    <div id="hd-infos">
                        <div id="hd-rate"></div>
                        <div id="hd-heart">"<3"</div>
                    </div>
                </div>
                <div id="hd-pictures"></div>
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
                                <div class="hd-tools-item"><?=$item->getIcon()?><span><?=$item->getLibelle()?></span></div>
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
                            <span id="nuits"><?=$Reservation->getNbJours()?> <?=$Reservation->getNbJours() > 1 ? 'nuits' : 'nuit'?></span> x <span data-prix="<?=$Hebergement->getPrix()?>" id="prix"><?=$Hebergement->getPrix()?> €</span> = <span id="total"><?=$Hebergement->getPrix() * $Reservation->getNbJours()?> €</span>
                            <div class="card-text">
                                <?php if($Reservation->getPrix() <= $Hebergement->getPrix() * $Reservation->getNbJours()){ ?>
                                <div>Réserver cet hébergement à la place de "<?=$OldHebergement->getLibelle()?>" vous coûtera <?=($Hebergement->getPrix() * $Reservation->getNbJours() - $Reservation->getPrix())?>€ de plus</div>
                                <?php } else { ?>
                                    <div>Réserver cet hébergement à la place de "<?=$OldHebergement->getLibelle()?>" vous fera économiser <?=$Reservation->getPrix() - ($Hebergement->getPrix() * $Reservation->getNbJours())?>€</div>
                                <?php } ?>
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

        <?php

        } else { ?>
            <div class="alert alert-warning">L'hôtel choisi n'est pas disponible pour la période souhaité</div>
    <?php }
        
    } else {?>
        <div class="alert alert-warning">Un problème est survenu, l'hebergement choisi n'est pas disponible dans la région de votre voyage ou n'existe simplement pas</div>
<?php }

} else { ?>
    <div class="alert alert-warning">Un problème est survenu avec les paramètres</div>
<?php } ?>
<?php
require_once "footer.php";
?>