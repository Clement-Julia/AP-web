<?php
require_once "header.php";
$avis = new Avis();
$luas = $avis->getHebergementbynonAvis($_SESSION["idUtilisateur"]);
$alls = $avis->getAllAvis();
?>

<div class="container mt-3">
    <div class="d-flex justify-content-center">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link background text-muted fs-4" id="pills-0-tab" data-bs-toggle="pill" data-bs-target="#pills-0" type="button" role="tab" aria-controls="pills-0" aria-selected="true">Laisser un avis</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link background text-muted fs-4" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Vos avis</button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade" id="pills-0" role="tabpanel" aria-labelledby="pills-0-tab">
            <?php
                $i = 0;
                foreach($luas as $lua){
                    ?>
                    <div class ="col-12 col-md-4 mt-3">
                        <div class="card text-center" style="width: 30rem;">
                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$i?>">
                                <img src="https://img2.freepng.fr/20180505/wle/kisspng-w-hotels-starwood-marriott-international-logo-5aed9c54873c61.5086030315255214925539.jpg" class="card-img-top" style= "height: 300px">
                                <div class="card-body">
                                    <h5 class="card-title"><?=$lua["libelle"]?></h5>
                                    <p class="card-text"><?= $lua["description"]?></p>
                                </div>
                            </button>
                        </div>
                        <form method="post" action="../controleurs/addAvis.php?id=<?=$lua["idHebergement"]?>">
                            <div class="modal fade" id="exampleModal<?=$i?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Publier un avis pour <?=$lua["libelle"]?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body pt-0">
                                            <!-- Système de rating -->
                                            <div class="rating">
                                                <input type="radio" name="rating" value="5" id="5">
                                                <label for="5">☆</label>

                                                <input type="radio" name="rating" value="4" id="4">
                                                <label for="4">☆</label>

                                                <input type="radio" name="rating" value="3" id="3">
                                                <label for="3">☆</label>

                                                <input type="radio" name="rating" value="2" id="2">
                                                <label for="2">☆</label>
                                                
                                                <input type="radio" name="rating" value="1" id="1" checked>
                                                <label for="1">☆</label>
                                            </div>
                                            <textarea class="form-control" name="commentaire" id="commentaire" placeholder="Votre avis..."></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Publier</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    $i++;
                }
            ?>
        </div>

        <div class="tab-pane fade mb-3" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="row">
            <?php
                foreach($alls as $all){
                    ?>
                    <div class ="col-12 col-md-4 mt-3">
                        <div class="card text-center" style="max-width: 30rem;">
                            <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#exampleModal<?=$i?>">
                                <img src="https://img2.freepng.fr/20180505/wle/kisspng-w-hotels-starwood-marriott-international-logo-5aed9c54873c61.5086030315255214925539.jpg"" class="card-img-top" style= "height: 300px">
                                <div class="card-body">
                                    <h5 class="card-title"><?=$all["libelle"]?></h5>
                                    <p class="card-text"><?= $all["description"]?></p>
                                </div>
                            </button>
                        </div>
                        <form method="post" action="../controleurs/modifAvis.php?id=<?=$all["idHebergement"]?>">
                            <div class="modal fade" id="exampleModal<?=$i?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Votre avis pour <?=$all["libelle"]?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body pt-0">
                                            <!-- Système de rating -->
                                            <div class="rating">
                                                <input type="radio" name="rating" value="5" id="5" <?= (5 == $all["note"]) ? "checked" : "" ?>>
                                                <label for="5">☆</label>

                                                <input type="radio" name="rating" value="4" id="4" <?= (4 == $all["note"]) ? "checked" : "" ?>>
                                                <label for="4">☆</label>

                                                <input type="radio" name="rating" value="3" id="3"<?= (3 == $all["note"]) ? "checked" : "" ?>>
                                                <label for="3">☆</label>

                                                <input type="radio" name="rating" value="2" id="2"<?= (2 == $all["note"]) ? "checked" : "" ?>>
                                                <label for="2">☆</label>
                                                
                                                <input type="radio" name="rating" value="1" id="1" <?= (1 == $all["note"]) ? "checked" : "" ?>>
                                                <label for="1">☆</label>
                                            </div>

                                            <!-- Commentaire -->
                                            <textarea class="form-control" name="commentaire" id="commentaire" placeholder="Votre avis..."><?=$all["commentaire"]?></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-warning" name="status" value="update">Modifier</button>
                                            <button type="submit" class="btn btn-danger" name="status" value="delete">Supprimer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                    $i++;
                }
            ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once "footer.php";
?>