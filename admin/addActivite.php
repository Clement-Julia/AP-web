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
                Tous les champs sont obligatoires
            </p>
        </div>
        <?php
    }
    if(isset($_GET["success"])){
        ?>
        <div class="container alert alert-success">
            <p>
                L'activité a bien été créé !
            </p>
        </div>
        <?php
    }
?>

<div class="container">
    <h1 class="mb-3">Ajout d'une activité :</h1>
    <form method="POST" action="../controleurs/addActivite.php" enctype="multipart/form-data">

        <div class="form-group mt-4">
            <label for="ville">Ville  : </label>
            <select class="selectpicker" name="ville" id="DataListVille" data-live-search="true" data-width="100%" title="Choisissez une ville">
                <?php
                    foreach($infos_v as $v){
                        ?>
                            <option value="<?= htmlspecialchars($v["libelle"], ENT_QUOTES) ?>" data-latitude = "<?= $v["latitude"] ?>" data-longitude = "<?= $v["longitude"] ?>"><?= htmlspecialchars($v["libelle"], ENT_QUOTES) ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>

        <div class="form-group mt-4">
            <label for="activite">Choix de l'icone de l'activité  : </label>
            <select class="selectpicker" name="activite" id="DataListActivite" data-live-search="true" data-width="100%" title="Choisissez un type d'activité">
                <?php
                    foreach($infos_a as $a){
                        ?>
                            <option value="<?= htmlspecialchars($a["libelle"], ENT_QUOTES) ?>"><?= htmlspecialchars($a["libelle"], ENT_QUOTES) ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="libelle">Description : </label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="10"></textarea>
        </div>
        <div class="form-group row mt-5">
            <div class="col-12 col-md-6 d-flex flex-column justify-content-center">
                <div class="form-group">
                    <label for="latitude">Latitude : </label>
                    <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Clicker sur la map pour la définir" autocomplete="off" disabled>
                    <input type="hidden" id="currentLatitude" name="currentLatitude">
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude : </label>
                    <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Clicker sur la map pour la définir" autocomplete="off" disabled>
                    <input type="hidden" id="currentLongitude" name="currentLongitude">
                </div>
            </div>
            <div class="col-12 col-md-6" id="map"></div>
        </div>
        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary">Ajouter</button>
        </div>

    </form>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="js/map.js"></script>

<?php
require_once "footerAdmin.php";