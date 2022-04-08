<?php
require_once "header.php";
$user = new Utilisateur($_SESSION["idUtilisateur"]);
$ReservationVoyage = new ReservationVoyage();
$test = $ReservationVoyage->getVoyageByUser($_SESSION["idUtilisateur"]);
    
$pastArray = [];
$todayArray = [];
$nextArray = [];
$today = new DateTime();
$today = $today->format('Y-m-d');

foreach($test as $key => $voyage){

    $dateDebut = "";
    $dateFin = "";

    foreach($voyage["voyage"] as $hebergement){
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
        usort($pastArray[$key]["voyage"], "sortFunctionDate");
    }
}
if(count($todayArray) > 0){
    foreach($todayArray as $key => $value){
        usort($todayArray[$key]["voyage"], "sortFunctionDate");
    }
}
if(count($nextArray) > 0){
    foreach($nextArray as $key => $value){
        usort($nextArray[$key]["voyage"], "sortFunctionDate");
    }
}

$count = implode($ReservationVoyage->getCountVoyageByUser($_SESSION["idUtilisateur"]));

error();

?>

<div class="container rounded mt-4 mb-5" id="form-container">
    <div class="row">
        <div class="col-md-3 border-right">

            <div class="d-flex flex-column align-items-center text-center p-3 pt-3">
                <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                <span class="font-weight-bold"><?= $user->getPrenom() . " " . $user->getNom() ?></span>
                <span class="font-weight-normal"><?= $user->getEmail(); ?></span>
                <span class="mt-3">
                    <a href="../controleurs/supUser.php?id=<?= $_SESSION["idUtilisateur"]?>" class='btn btn-danger mt-1'>Supprimer le compte</a>
                </span>
            </div>

            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                
                <h6 class="text-center text-decoration-underline">Compte</h6>
                <button class="border-profil text-profil nav-link <?= (empty($_GET["tab"]) && empty($_GET["fav"])) ? "active" : "" ?>" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">
                <i class="fas fa-id-card me-2"></i>Informations personnelles
                </button>
                <button class="border-profil text-profil nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                    <i class="fas fa-shield-alt me-2"></i>Connexion & Sécurité
                </button>

                <h6 class="text-center text-decoration-underline mt-3">Achats</h6>
                <button class="border-profil text-profil nav-link <?= (!empty($_GET["tab"])) ? "active" : "" ?>" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                    <i class="fas fa-clock me-2"></i>Historique
                </button>
                <button class="border-profil text-profil nav-link <?= (!empty($_GET["fav"])) ? "active" : "" ?>" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                    <i class="fas fa-heart me-2"></i>Favoris
                </button>
            </div>
        </div>

        <div id="historique" class="col-md-8 mt-3 border-right">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade <?= (empty($_GET["histo"]) && empty($_GET["fav"])) ? "active show" : "" ?>" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Informations personnelles</h4>
                        </div>
                        <form method="post" action="../controleurs/updateUser.php?update=info">
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="labels">Nom</label>
                                    <input type="text" name="nom" class="form-control" placeholder="Nom" value="<?= htmlspecialchars($user->getNom(), ENT_QUOTES) ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="labels">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user->getPrenom(), ENT_QUOTES) ?>" placeholder="Prénom">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 mt-3">
                                    <label class="labels">Âge</label>
                                    <input type="text" name="age" class="form-control" placeholder="Entrez votre âge" value="<?= $user->getAgeByDate() ?>" disabled>
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
                                    <input type="text" name="email" class="form-control" placeholder="Entrez votre email" value="<?= htmlspecialchars($user->getEmail(), ENT_QUOTES) ?>">
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
                <div class="tab-pane fade <?= (!empty($_GET["histo"])) ? "active show" : "" ?> mt-3" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                    <div class="d-flex justify-content-center">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="background text-light fs-5 nav-link <?= (!empty($_GET["histo"]) && $_GET["histo"] == "past") ? "active show" : "" ?>" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Réalisé</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="background text-light fs-5 nav-link <?= (!empty($_GET["histo"]) && $_GET["histo"] == "current") ? "active show" : "" ?>" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">En cours</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="background text-light fs-5 nav-link <?= (!empty($_GET["histo"]) && $_GET["histo"] == "next") ? "active show" : "" ?>" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">À venir</button>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade mb-2 <?= (empty($_GET["histo"]) || $_GET["histo"] == "past") ? "active show" : "" ?>" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <?php
                                $index = 1;
                                $limit = 0;
                                if($pastArray){
                                    foreach($pastArray as $voyage){
                                        $date = $ReservationVoyage->getDateVoyage($voyage["id"]);
                                        ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-heading<?= $voyage["id"] ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $voyage["id"] ?>" aria-expanded="false" aria-controls="flush-collapse<?= $voyage["id"] ?>">
                                                Voyage du <?= dateToFr($date["dateDebut"]) ?> au <?= dateToFr($date["dateFin"]) ?>
                                                </button>
                                            </h2>
                                            <div id="flush-collapse<?= $voyage["id"] ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $voyage["id"] ?>" data-bs-parent="#accordionFlushExample">
                                            <?php
                                                foreach($voyage["voyage"] as $etapes){
                                                    ?>
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
                                                                        <?=dateToFr($etapes['dateDebut'])?>
                                                                    </div>
                                                                    <div>
                                                                        <span class="fw-bold">Date de départ :</span>
                                                                        <?=dateToFr($etapes['dateFin'])?>
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
                                                    <?php
                                                    $index++;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        $index = 1;
                                        $limit++;
                                        if($limit == 5 && empty($_GET["histo"]) || $limit == 5 && !empty($_GET["histo"]) && $_GET["histo"] != "past"){
                                            break;
                                        }
                                    }
                                    if(empty($_GET["histo"]) || !empty($_GET["histo"]) && $_GET["histo"] != "past"){
                                        if(count($pastArray) > 5){ ?>
                                            <div class="d-flex justify-content-center mt-3">
                                                <a href="?histo=past"><button class="btn btn-primary">Tout afficher</button></a>
                                            </div>
                                        <?php }
                                    }
                                }else{
                                    ?>
                                    <div class="alert alert-warning mt-2">Il semblerait que vous n'ayez pas effectué de voyage...</div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade mb-2 <?= (!empty($_GET["histo"]) && $_GET["histo"] == "current") ? "active show" : "" ?>" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <?php
                                $index = 1;
                                $limit = 0;
                                if($todayArray){
                                    foreach($todayArray as $voyage){
                                        $date = $ReservationVoyage->getDateVoyage($voyage["id"]);
                                        ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-heading<?= $voyage["id"] ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $voyage["id"] ?>" aria-expanded="false" aria-controls="flush-collapse<?= $voyage["id"] ?>">
                                                    Voyage du <?= dateToFr($date["dateDebut"]) ?> au <?= dateToFr($date["dateFin"]) ?>
                                                </button>
                                            </h2>
                                            <div id="flush-collapse<?= $voyage["id"] ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $voyage["id"] ?>" data-bs-parent="#accordionFlushExample">
                                                <?php
                                                foreach($voyage["voyage"] as $etapes){
                                                    ?>
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
                                                        <?php
                                                    $index++;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        $index = 1;
                                        $limit++;
                                        if($limit == 5 && empty($_GET["histo"]) || $limit == 5 && !empty($_GET["histo"]) && $_GET["histo"] != "current"){
                                            break;
                                        }
                                    }
                                    if(empty($_GET["histo"]) || !empty($_GET["histo"]) && $_GET["histo"] != "current"){ 
                                        if(count($todayArray) > 5){ ?>
                                            <div class="d-flex justify-content-center mt-3">
                                                <a href="?histo=current"><button class="btn btn-primary">Tout afficher</button></a>
                                            </div>
                                        <?php }
                                    }
                                }else{
                                    ?>
                                    <div class="alert alert-warning mt-2">Il semblerait que vous n'avez pas de voyage en cours...</div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade mb-2 <?= (!empty($_GET["histo"]) && $_GET["histo"] == "next") ? "active show" : "" ?>" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                <?php
                                $index = 1;
                                $limit = 0;
                                if($nextArray){
                                    foreach($nextArray as $voyage){
                                        $date = $ReservationVoyage->getDateVoyage($voyage["id"]);
                                        ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="flush-heading<?= $voyage["id"] ?>">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?= $voyage["id"] ?>" aria-expanded="false" aria-controls="flush-collapse<?= $voyage["id"] ?>">
                                                Voyage du <?= dateToFr($date["dateDebut"]) ?> au <?= dateToFr($date["dateFin"]) ?>
                                                </button>
                                            </h2>
                                            <div id="flush-collapse<?= $voyage["id"] ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?= $voyage["id"] ?>" data-bs-parent="#accordionFlushExample">
                                                <?php
                                                foreach($voyage["voyage"] as $etapes){
                                                    ?>
                                                
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
                                                        <?php
                                                    $index++;
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <?php
                                        $index = 1;
                                        $limit++;
                                        if($limit == 5 && empty($_GET["histo"]) || $limit == 5 && !empty($_GET["histo"]) && $_GET["histo"] != "next"){
                                            break;
                                        }
                                    }
                                    if(empty($_GET["histo"]) || !empty($_GET["histo"]) && $_GET["histo"] != "next"){
                                        if(count($nextArray) > 5){ ?>
                                            <div class="d-flex justify-content-center mt-3">
                                                <a href="?histo=next"><button class="btn btn-primary">Tout afficher</button></a>
                                            </div>
                                        <?php }
                                    }
                                }else{
                                    ?>
                                    <div class="alert alert-warning mt-2">Il semblerait que vous n'avez pas encore effectué de voyage...</div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade <?= (!empty($_GET["fav"])) ? "active show" : "" ?> mt-5" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    <?php
                        $Favoris = new Favoris();
                        $allFavoris = $Favoris->getAllFavorisForUser($_SESSION['idUtilisateur']);
                    ?>
                    <div id="favoris-container" class="my-3">
                        <?php
                        if(count($allFavoris) > 0){
                            $limit = 0;
                            foreach($allFavoris as $favoris){
                                $Hebergement = new Hebergement($favoris['idHebergement']);
                                $Images = new Images($Hebergement->getUuid());
                                $banniere = $Images->getBanniere();
                                ?>
                                <div data-idhebergement="<?=$Hebergement->getIdHebergement()?>" class="card mb-3">
                                    <div class="row g-0 d-flex align-items-center">
                                        <div class="col-md-3">
                                            <img src="<?=$banniere?>" class="img-fluid rounded-start fav-size">
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
                                $limit++;
                                if($limit == 5 && !isset($_GET["fav"])){
                                    break;
                                }
                            }
                            if(!isset($_GET["fav"])){
                                if(count($allFavoris) > 5){?>
                                    <div class="d-flex justify-content-center mt-3">
                                        <a href="?fav=all"><button class="btn btn-primary">Tout afficher</button></a>
                                    </div>
                                <?php }
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