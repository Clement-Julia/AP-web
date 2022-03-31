<?php
require_once "header.php";
$avis = new Avis();
$luas = $avis->getHebergementbynonAvis($_SESSION["idUtilisateur"]);
$alls = $avis->getAllAvis();
?>

<?php
if(isset($_GET["success"]) && isset($_GET["update"])){
    ?>
    <div class="container alert alert-success mt-3">
        <p>
            L'avis a bien été modifié
        </p>
    </div>
    <?php
}
if(isset($_GET["success"]) && isset($_GET["delete"])){
    ?>
    <div class="container alert alert-success mt-3">
        <p>
            L'avis a bien été supprimé
        </p>
    </div>
    <?php
}
if(!empty($_GET["error"]) && $_GET["error"] == "rating"){
    ?>
    <div class="container alert alert-warning mt-3">
        <p>
            Les avis doivent être noté avec une note minimale de 1 étoile
        </p>
    </div>
    <?php
}

if(!empty($_SESSION['idUtilisateur'])){
?>
    <div class="container mt-3">
        <div class="d-flex justify-content-center">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link background text-light fs-4 active" id="pills-0-tab" data-bs-toggle="pill" data-bs-target="#pills-0" type="button" role="tab" aria-controls="pills-0" aria-selected="true">Laisser un avis</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link background text-light fs-4" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Vos avis</button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active mb-3" id="pills-0" role="tabpanel" aria-labelledby="pills-0-tab">
                <div class="row">
                <?php
                    $x = 1;
                    if(count($luas)){
                        foreach($luas as $lua){
                            $HebergementTemp = new Hebergement($lua["idHebergement"]);
                            $Image = new Images($HebergementTemp->getUuid());
                            ?>
                            <div class ="col-12 col-md-4 mt-3">
                                <div class="card text-center" style="max-width: 30rem;">
                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$x?>">
                                        <img src="<?=$Image->getBanniere()?>" class="card-img-top" style= "height: 300px">
                                        <div class="card-body">
                                            <h5 class="card-title"><?=$lua["libelle"]?></h5>
                                            <p class="card-text"><?= $lua["description"]?></p>
                                        </div>
                                    </button>
                                </div>
                            </div>
                            <?php
                            $x++;
                        }
                    }else{
                        ?>
                        <div class="alert alert-warning mt-2">Il semblerait que vous n'ayez pas encore effectué de voyage...</div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <div class="tab-pane fade mb-3" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <div class="row">
                    <?php
                    if(count($alls)){
                        foreach($alls as $all){
                            $HebergementTemp = new Hebergement($all["idHebergement"]);
                            $Image = new Images($HebergementTemp->getUuid());
                            ?>
                            <div class ="col-12 col-md-4 mt-3">
                                <div class="card text-center" style="max-width: 30rem;">
                                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$all["idAvis"]?>">
                                        <img src="<?=$Image->getBanniere()?>" class="card-img-top" style= "height: 300px">
                                        <div class="card-body" style="max-height: 85px;">
                                            <h5 class="card-title"><?=$all["libelle"]?></h5>
                                            <p class="card-text text-truncate"><?= $all["description"]?></p>
                                        </div>
                                    </button>
                                </div>
                                
                            </div>
                            <?php
                        }
                    }else{
                        ?>
                        <div class="alert alert-warning mt-2">Il semblerait que vous n'ayez pas encore laissé d'avis...</div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php

foreach($luas as $lua){
    $x = 1;
    ?>
    <div class="modal fade" id="exampleModal<?=$x?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../controleurs/addAvis.php?id=<?=$lua['idHebergement']?>&dateFin=<?=$lua['dateFin'];?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">Publier un avis pour <?=$lua["libelle"]?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="rating">
                            <input type="radio" name="rating" value="5" id="<?=$x?>">
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>

                            <input type="radio" name="rating" value="4" id="<?=$x?>">
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>

                            <input type="radio" name="rating" value="3" id="<?=$x?>">
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>

                            <input type="radio" name="rating" value="2" id="<?=$x?>">
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>
                            
                            <input type="radio" name="rating" value="1" id="<?=$x?>" checked>
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>
                        </div>
                        <textarea class="form-control" name="commentaire" id="commentaire" placeholder="Votre avis..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Publier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
}

foreach($alls as $all){
    ?>
    <div class="modal fade" id="exampleModal<?=$all["idAvis"]?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="post" action="../controleurs/modifAvis.php?id=<?=$all["idAvis"]?>" style="z-index: 1060">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-dark" id="exampleModalLabel">Votre avis pour <?=$all["libelle"]?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <div class="rating">
                            <input type="radio" name="rating" value="5" id="<?=$x?> <?= (5 == $all["note"]) ? "checked" : "" ?>">
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>

                            <input type="radio" name="rating" value="4" id="<?=$x?>" <?= (4 == $all["note"]) ? "checked" : "" ?>>
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>

                            <input type="radio" name="rating" value="3" id="<?=$x?>" <?= (3 == $all["note"]) ? "checked" : "" ?>>
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>

                            <input type="radio" name="rating" value="2" id="<?=$x?>" <?= (2 == $all["note"]) ? "checked" : "" ?>>
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>
                            
                            <input type="radio" name="rating" value="1" id="<?=$x?>" <?= (1 == $all["note"]) ? "checked" : "" ?>>
                            <label for="<?=$x?>">☆</label>
                            <?php $x++ ?>
                        </div>
                        <textarea class="form-control" name="commentaire" id="commentaire" placeholder="Votre avis..."><?= htmlspecialchars($all["commentaire"], ENT_QUOTES) ?></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning" name="status" value="update">Modifier</button>
                        <button type="submit" class="btn btn-danger" name="status" value="delete">Supprimer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php
}

} else { ?>
    <div class="alert alert-warning">Vous devez être connecté pour accéder à ce contenu</div>
<?php } ?>

<?php
require_once "footer.php";
?>