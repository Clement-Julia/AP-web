<?php
require_once "header.php";
$user = new Utilisateur($_SESSION["idUtilisateur"]);
$test = new ReservationVoyage();
$test = $test->getVoyageByUser($_SESSION["idUtilisateur"]);

$pastArray = [];
$todayArray = [];
$nextArray = [];
$today = new DateTime();
$today = $today->format('Y-m-d');

foreach($test as $key => $voyage){

    $dateDebut = "";
    $dateFin = "";

    foreach($voyage as $hebergement){
        if($dateDebut == "" || $dateDebut > $hebergement['dateDebut']){
            $dateDebut = $hebergement['dateDebut'];
        }
        if($dateFin == "" || $dateFin < $hebergement['dateFin']){
            $dateFin = $hebergement['dateFin'];
        }
    }

    $dateDebut = new DateTime($dateDebut);
    $dateDebut = $dateDebut->format('Y-m-d');
    $dateFin = new DateTime($dateFin);
    $dateFin = $dateFin->format('Y-m-d');

    if($today > $dateFin){
        $pastArray[] = $test[$key];
    } else if($today < $dateDebut){
        $nextArray[] = $test[$key];
    } else {
        $todayArray[] = $test[$key];
    }

}

if(count($pastArray) > 0){
    foreach($pastArray as $key => $value){
        usort($pastArray[$key], "sortFunctionDate");
    }
}
if(count($todayArray) > 0){
    foreach($todayArray as $key => $value){
        usort($todayArray[$key], "sortFunctionDate");
    }
}
if(count($nextArray) > 0){
    foreach($nextArray as $key => $value){
        usort($nextArray[$key], "sortFunctionDate");
    }
}

$id = 0;
error($_GET);

?>

<div class="container rounded bg-white mt-3">
    <div class="d-flex justify-content-center">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="background text-muted fs-5 nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Réalisé</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="background text-muted fs-5 nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">En cours</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="background text-muted fs-5 nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">À venir</button>
            </li>
        </ul>
    </div>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <?php
                $i = 1;
                $index = 1;
                foreach($pastArray as $voyage){
                    ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading<?= $id ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $id ?>" aria-expanded="false" aria-controls="flush-collapse<?= $id ?>">
                                    Voyage <?= $i ?>
                                </button>
                            </h2>
                    <?php
                    foreach($voyage as $etapes){
                        ?>
                        
                            <div id="flush-collapse<?= $id ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $id ?>" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="mx-3 card">
                                        <div class="card-header"><h6>Etape : <?=$index?></h6></div>
                                        <div class="card-body">
                                            <div>
                                                <span class="fw-bold">Ville :</span>
                                                <?=$etapes['ville']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Hébergement :</span>
                                                <?=$etapes['hebergement']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Description hébergement :</span>
                                                <?=$etapes['description']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Date d'arrivée :</span>
                                                <?=$etapes['dateDebut']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Date de départ :</span>
                                                <?=$etapes['dateFin']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Code réservation :</span>
                                                <?=$etapes['code']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Prix :</span>
                                                <?=$etapes['prix']?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        $index++;
                    }
                    ?>
                    </div>
                    <?php
                    $id++;
                }
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <?php
                $i = 1;
                $index = 1;
                foreach($todayArray as $voyage){
                    ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading<?= $id ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $id ?>" aria-expanded="false" aria-controls="flush-collapse<?= $id ?>">
                                    Voyage <?= $i ?>
                                </button>
                            </h2>
                    <?php
                    foreach($voyage as $etapes){
                        ?>
                        
                            <div id="flush-collapse<?= $id ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $id ?>" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="mx-3 card">
                                        <div class="card-header"><h6>Etape : <?=$index?></h6></div>
                                        <div class="card-body">
                                            <div>
                                                <span class="fw-bold">Ville :</span>
                                                <?=$etapes['ville']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Hébergement :</span>
                                                <?=$etapes['hebergement']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Description hébergement :</span>
                                                <?=$etapes['description']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Date d'arrivée :</span>
                                                <?=$etapes['dateDebut']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Date de départ :</span>
                                                <?=$etapes['dateFin']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Code réservation :</span>
                                                <?=$etapes['code']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Prix :</span>
                                                <?=$etapes['prix']?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        $index++;
                    }
                    ?>
                    </div>
                    <?php
                    $id++;
                }
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <?php
                $i = 1;
                $index = 1;
                foreach($nextArray as $voyage){
                    ?>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-heading<?= $id ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $id ?>" aria-expanded="false" aria-controls="flush-collapse<?= $id ?>">
                                    Voyage <?= $i ?>
                                </button>
                            </h2>
                    <?php
                    foreach($voyage as $etapes){
                        ?>
                        
                            <div id="flush-collapse<?= $id ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $id ?>" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="mx-3 card">
                                        <div class="card-header"><h6>Etape : <?=$index?></h6></div>
                                        <div class="card-body">
                                            <div>
                                                <span class="fw-bold">Ville :</span>
                                                <?=$etapes['ville']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Hébergement :</span>
                                                <?=$etapes['hebergement']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Description hébergement :</span>
                                                <?=$etapes['description']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Date d'arrivée :</span>
                                                <?=$etapes['dateDebut']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Date de départ :</span>
                                                <?=$etapes['dateFin']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Code réservation :</span>
                                                <?=$etapes['code']?>
                                            </div>
                                            <div>
                                                <span class="fw-bold">Prix :</span>
                                                <?=$etapes['prix']?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        $index++;
                    }
                    ?>
                    </div>
                    <?php
                    $id++;
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once "footer.php";
?>