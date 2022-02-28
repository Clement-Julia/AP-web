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
                $folder = scandir("../assets/src/tuuid/".$hotel->getUuid());
                for($i = 2; $i < count($folder); $i++){
                    $ext = substr($folder[$i], strrpos($folder[$i], '.'));
                    if(strtok($folder[$i], '.') == "banniere"){
                        ?>
                        <img src="../assets/src/tuuid/<?=$hotel->getUuid()?>/<?=$folder[$i]?>" name="banniere" class="img-fluid rounded float-start badgetest <?= (!empty(glob($filename))) ? "" : "d-none" ?>" style="max-width: 300px">
                        <?php
                    }
                }
                ?>
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
            $img = scandir("../assets/src/tuuid/".$hotel->getUuid());
            if(count($img) <= 2){
                ?>
                    <span class="text-muted font-italic">Aucune photo n'a été demandée</span>
                <?php
            }else{
                lister_images("../assets/src/tuuid/".$hotel->getUuid());
            }
            ?>
        </div>
    </div>

    <a href="validPicture.php" class="btn btn-secondary mt-5 mb-3"><i class="fas fa-arrow-left"></i></a>
</div>

<?php
require_once "footerAdmin.php";