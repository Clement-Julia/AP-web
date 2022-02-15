<?php
require_once "headerAdmin.php";

$region = new Region();
$infos = $region->getAllregion();

if(!empty($_GET["id"])){
    $verif = 0;
    foreach($infos as $info){
        if($info["idRegion"] == $_GET["id"]){
            $verif = 1;
        }
    }
    if($verif == 0){
        $_GET["id"] = "error";
    }
    if($_GET["id"] == "error"){
        ?>
        <div class="container alert alert-danger">
            <p>
                La région n'existe pas
            </p>
        </div>
        <?php
    }   
}
if(!empty($_GET["error"]) && $_GET["error"] == "crash"){
    ?>
    <div class="container alert alert-danger">
        <p>
            La fonctionnalité est actuellement indisponible <br>
            Pour plus d'information contacter le développeur
        </p>
    </div>
    <?php
}
if(!empty($_GET["error"]) && $_GET["error"] == "all"){
    ?>
    <div class="container alert alert-danger">
        <p>
            Vous devez définir une seule manière de position de ville et elle doit avoir au minimum une image
        </p>
    </div>
    <?php
}
if(isset($_GET["success"])){
    ?>
    <div class="container alert alert-success">
        <p>
            La région a bien été créée !
        </p>
    </div>
    <?php
}
$bool = 0;
?>

<div class="container">
    <h1 class="mb-3">Modification d'une Région :</h1>
    <?php
    if(empty($_GET["id"]) || $_GET["id"] == "error"){
        ?>
            <form method="GET" action="modifRegion.php">

                <div class="form-group text-center">
                    <label for="libelle">Région  : </label>
                    <input class="form-control" list="datalistOptions" name="id" id="exampleDataList" placeholder="Entrez le nom de larégion à modifier" required autocomplete="off">
                    <datalist id="datalistOptions">
                        <?php
                            foreach($infos as $info){
                                ?>
                                    <option value="<?= $info["idRegion"] ?>"><?= htmlspecialchars($info["libelle"], ENT_QUOTES) ?></option>
                                <?php
                            }
                        ?>
                    </datalist>
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </div>

            </form>
        
        <?php
    }else{
        $info_region = new Region($_GET["id"]);
        ?>
        <form method="POST" action="../controleurs/modifRegion.php?id=<?= $info_region->getIdRegion() ?>">
            <div class="form-group">
                <label for="libelle">Region : </label>
                <input type="text" class="form-control" name="libelle" id="libelle" placeholder="Entrez le nom d'une ville" required autocomplete="off" value="<?= htmlspecialchars($info_region->getLibelle(), ENT_QUOTES) ?>" disabled>
            </div>

            <div class="form-group">
                <label for="description">Description : </label>
                <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Entrez la description de la région"><?= htmlspecialchars($info_region->getDescription(), ENT_QUOTES) ?></textarea>
            </div>

            <div class="form-group text-center mt-4">
                <button type="submit" class="btn btn-warning">Modifier</button>
            </div>

        </form>
        <a href="modifRegion.php" class="btn btn-secondary mt-5"><i class="fas fa-arrow-left"></i></a>
        <?php
    }
    ?>
</div>


<?php
require_once "footerAdmin.php";