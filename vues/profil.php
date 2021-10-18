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

<div class="container rounded bg-white">
    <div class="row">
        <div class="col-md-3 border-right">

            <div class="d-flex flex-column align-items-center text-center p-3 pt-3">
                <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                <span class="font-weight-bold"><?= $user->getPrenom() . " " . $user->getNom() ?></span>
                <span class="text-black-50"><?= $user->getEmail(); ?></span>
                <span class="mt-3">
                    <!-- <a href="../controleurs/deconnexion.php" class='btn btn-outline-danger'>Désactiver le compte</a> -->
                    <a href="../controleurs/supUser.php?id=<?= $_SESSION["idUtilisateur"]?>" class='btn btn-outline-danger mt-1'>Supprimer le compte</a>
                </span>
            </div>

            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                
                <h6 class="text-center text-decoration-underline">Compte</h6>
                <button class="border-profile text-muted nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">
                <i class="fas fa-id-card me-2"></i>Informations personnelles
                </button>
                <button class="border-profile text-muted nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                    <i class="fas fa-shield-alt me-2"></i>Connexion & Sécurité
                </button>

                <h6 class="text-center text-decoration-underline mt-3">Achats</h6>
                <button class="border-profile text-muted nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                    <i class="fas fa-clock me-2"></i>Historique
                </button>
                <button class="border-profile text-muted nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                    <i class="fas fa-heart me-2"></i>Favoris
                </button>
            </div>
        </div>

        <div class="col-md-8 mt-3 border-right">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Informations personnelles</h4>
                        </div>
                        <form method="post" action="../controleurs/updateUser.php?update=info">
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="labels">Nom</label>
                                    <input type="text" name="nom" class="form-control" placeholder="Nom" value="<?= $user->getNom() ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="labels">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" value="<?= $user->getPrenom() ?>" placeholder="Prénom">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 mt-3">
                                    <label class="labels">Âge</label>
                                    <input type="number" name="age" class="form-control" placeholder="Entrez votre âge" value="<?= $user->getAge() ?>">
                                </div>
                            </div>
                            <div class="mt-5 text-center">
                                <button class="btn btn-primary profile-button" type="submit">Sauvegarder les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Connexion & Sécurité</h4>
                        </div>
                        <div class="row mt-3">
                            <form method="post" action="../controleurs/updateUser.php?update=co">
                                <div class="col-md-12">
                                    <label class="labels">Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Entrez votre email" value="<?= $user->getEmail() ?>">
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-5">
                                    <h4 class="text-right">Changer son mot-de-passe</h4>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <label class="labels">Ancien mot-de-passe :</label>
                                    <input type="text" name="current_mdp" class="form-control" placeholder="Entrez votre mot-de-passe actuel" value="">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="labels">Nouveau mot-de-passe :</label>
                                    <input type="text" name="new_mdp" class="form-control" placeholder="Entrez votre nouveau mot-de-passe" value="">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="labels">Vérification nouveau mot-de-passe :</label>
                                    <input type="text" name="new_verif" class="form-control" placeholder="Entrez à nouveau votre nouveau mot-de-passe" value="">
                                </div>
                                <div class="mt-5 text-center">
                                    <button class="btn btn-primary profile-button" type="submit">Sauvegarder les modifications</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade mt-3" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
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
                                    $index = 1;
                                    $i++;
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
                                    $i++;
                                    $index = 1;
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
                                    $i++;
                                    $index = 1;
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade mt-5" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php
                        $Favoris = new Favoris();
                        $allFavoris = $Favoris->getAllFavorisForUser($_SESSION['idUtilisateur']);
                    ?>
                    <div id="favoris-container" class="my-3">
                        <?php
                        if(count($allFavoris) > 0){

                            foreach($allFavoris as $favoris){
                                $Hebergement = new Hebergement($favoris['idHebergement']);
                                $Images = new Images($Hebergement->getUuid());
                                $banniere = $Images->getBanniere();
                                ?>
                                <div data-idhebergement="<?=$Hebergement->getIdHebergement()?>" class="card mb-3">
                                    <div class="row g-0 d-flex align-items-center">
                                        <div class="col-md-3">
                                            <img src="<?=$banniere?>" class="img-fluid rounded-start" alt="...">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body">
                                                <h5 class="card-title"><?=$Hebergement->getLibelle()?></h5>
                                                <p class="card-text"><?=$Hebergement->getDescription()?></p>
                                                <div class="text-muted"><?=$Hebergement->getVilleLibelle($Hebergement->getIdVille())?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-1 heart-container my-2">
                                            <i id="<?=$Hebergement->getIdHebergement()?>" class='fas fa-heart fa-2x'></i>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                        
                        <script src="../assets/js/favoris.js"></script>
                    
                        <?php
                        } else {
                            ?>
                            <div class="alert alert-warning">Il semblerait que vous n'ayez pas encore trouvé d'hôtel favoris...</div>
                            <?php
                        } 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php
require_once "footer.php";
?>