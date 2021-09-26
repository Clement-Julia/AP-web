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
                <button class="nav-link test text-muted fs-4" id="pills-0-tab" data-bs-toggle="pill" data-bs-target="#pills-0" type="button" role="tab" aria-controls="pills-0" aria-selected="true">Laisser un avis</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link test text-muted fs-4" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Vos avis</button>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade" id="pills-0" role="tabpanel" aria-labelledby="pills-0-tab">
            <?php
                foreach($luas as $lua){
                    ?>
                    <div class ="col-6 mt-3">
                        <div class="card text-center" style="width: 30rem;">
                            <a href="#" class="mb-3 lien">
                                <!-- <img src="<?=$all["photo"]?>" class="card-img-top" style= "height: 300px"> -->
                                <div class="card-body">
                                    <h5 class="card-title"><?=$lua["libelle"]?></h5>
                                    <p class="card-text"><?= $lua["description"]?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            ?>
        </div>

        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <?php
            
                foreach($alls as $all){
                    ?>
                    <div class ="col-6 mt-3">
                        <div class="card text-center" style="width: 30rem;">
                            <a href="#" class="mb-3 lien">
                                <!-- <img src="<?=$all["photo"]?>" class="card-img-top" style= "height: 300px"> -->
                                <div class="card-body">
                                    <h5 class="card-title"><?=$all["libelle"]?></h5>
                                    <p class="card-text"><?= $all["description"]?></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>
</div>

<?php
require_once "footer.php";
?>