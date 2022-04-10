<?php
require_once("headerAdmin.php");
$response = file_get_contents('https://geo.api.gouv.fr/regions');
$response = json_decode($response);

$region = new Region();
$regions = $region->getAllregions();
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
?>

<div class="container">
    <h1 class="mb-3">Ajout d'une Région :</h1>
    <form method="POST" action="../controleurs/addRegion.php">

        <div class="form-group">
            <label for="region">Région : </label>
            <select class="custom-select" id="region" aria-label="Default select example" name="region" required>
                <option selected disabled>Selectionnez la région</option>
                <?php
                    foreach($response as $region){
                        if(empty($regions[$region->nom])){
                            ?>
                                <option value="<?= $region->nom ?>"><?= $region->nom ?></option>
                            <?php
                        }
                    }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description : </label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Entrez la description de la ville"></textarea>
        </div>

        <div class="form-group">
            <div class="form-group mt-4">
                <label for="latitude">Latitude : </label>
                <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Entrez une latitude" autocomplete="off" disabled>
                <input type="hidden" name="currentLatitude" id="currentLatitude">
            </div>

            <div class="form-group">
                <label for="longitude">Longitude : </label>
                <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Entrez une longitude" autocomplete="off" disabled>
                <input type="hidden" name="currentLongitude" id="currentLongitude">
            </div>
        </div>
    
        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary" name="submit">Ajouter</button>
        </div>

    </form>
</div>

<?php
require_once("footerAdmin.php");
?>