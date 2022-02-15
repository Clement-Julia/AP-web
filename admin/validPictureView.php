<?php
require_once "headerAdmin.php";

$hotel = new Hebergement($_GET["id"]);
?>

<div class="container">
    <h1 class="mb-5">Demande d'ajout de photos pour l'hébergement <?= htmlspecialchars($hotel->getLibelle(), ENT_QUOTES) ?> :</h1>
    <div class="form-group">
        <label>Bannière :</label>
        <div id="banniere">
            <?php 
                $filename = "../assets/src/tuuid/" . $hotel->getUuid() . "/banniere.*";
                ?>
                <img src="../assets/src/tuuid/<?=$hotel->getUuid()?>/banniere" name="banniere" class="img-fluid rounded float-start badgetest <?= (!empty(glob($filename))) ? "" : "d-none" ?>" style="max-width: 300px">
                <?php
                if(empty(glob($filename))){
                    ?>
                        <span class="text-muted font-italic">Aucune banière n'a été demandée</span>
                    <?php
                }
            ?>
        </div>
    </div>
    <div class="form-group">
        <label>Images :</label>
        <div id="image">
            <?php
            $img = lister_images("../assets/src/tuuid/".$hotel->getUuid());
            if(empty($img)){
                ?>
                    <span class="text-muted font-italic">Aucune photo n'a été demandée</span>
                <?php
            }
                
            ?>
        </div>
    </div>

    <a href="validPicture.php" class="btn btn-secondary mt-5 mb-3"><i class="fas fa-arrow-left"></i></a>
</div>

<?php
require_once "footerAdmin.php";