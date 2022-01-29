<?php
require_once "headerAdmin.php";

$villes = new Ville();
$infos_v = $villes->getAllville();

$activités = new Activite();
$infos_a = $activités->getAllActivite();
?>

<?php
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
                Vous devez définir une seule manière de position pour l'hebergement et il doit avoir au minimum une image
            </p>
        </div>
        <?php
    }
    if(isset($_GET["success"])){
        ?>
        <div class="container alert alert-success">
            <p>
                L'hébergement a bien été créé !
            </p>
        </div>
        <?php
    }
?>

<div class="container">
    <h1 class="mb-3">Ajout d'une activité :</h1>
    <form method="POST" action="../controleurs/addActivite.php" enctype="multipart/form-data">

        <div class="form-group mt-4">
            <label for="libelle">Ville : </label>
            <input class="form-control" list="datalistVille" name="ville" id="DataListVille" placeholder="Entrez le type d'activité" required autocomplete="off">
            <datalist id="datalistVille">
                <?php
                    foreach($infos_v as $v){
                        ?>
                            <option value="<?= $v["libelle"] ?>" data-latitude="<?= $v["latitude"] ?>" data-longitude="<?= $v["longitude"] ?>"></option>
                        <?php
                    }
                ?>
            </datalist>
        </div>

        <div class="form-group mt-4">
            <label for="libelle">Type d'activité : </label>
            <input class="form-control" list="datalistActivite" name="activite" id="DataListActivite" placeholder="Entrez le type d'activité" required autocomplete="off">
            <datalist id="datalistActivite">
                <?php
                    foreach($infos_a as $a){
                        ?>
                            <option value="<?= $a["libelle"] ?>"></option>
                        <?php
                    }
                ?>
            </datalist>
        </div>
        <div class="form-group">
            <label for="libelle">Description : </label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="10"></textarea>
        </div>
        <div class="form-group row mt-5">
            <div class="col-6 d-flex flex-column justify-content-center">
                <div class="form-group">
                    <label for="latitude">Latitude : </label>
                    <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Entrez une latitude" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude : </label>
                    <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Entrez une longitude" autocomplete="off">
                </div>
            </div>
            <div class="col-6" id="map"></div>
        </div>
        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>

    </form>
</div>

<?php
require_once "footerAdmin.php";