<?php
require_once "headerAdmin.php";
require_once "../Modeles/Modele_All.php";
$region = new Region();
$infos = $region->getAllregion();
?>


<div class="container">
    <h1 class="mb-3">Ajout d'une ville :</h1>
    <form method="POST" action="../controleurs/addVille.php" enctype="multipart/form-data">

        <div class="form-group">
            <label for="libelle">Nom : </label>
            <input type="text" class="form-control" name="libelle" id="libelle" placeholder="Entrez le nom d'une ville" required autocomplete="off">
        </div>

        <div class="form-group">
            <label for="region">Appartenance : </label>
            <select class="custom-select" aria-label="Default select example" name="region" required>
                <option selected disabled>Selectionnez le nom de la région</option>
                <?php
                    foreach($infos as $info){
                        ?>
                            <option value="<?= $info["idRegion"] ?>"><?= $info["libelle"] ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="description">Description : </label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Entrez la description de la ville"></textarea>
        </div>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-link text-muted" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Via coordonnées</a>
                <a class="nav-link text-muted" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Via lien google map</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="form-group mt-4">
                    <label for="latitude">Latitude : </label>
                    <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Entrez une latitude" autocomplete="off">
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude : </label>
                    <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Entrez une longitude" autocomplete="off">
                </div>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="form-group mt-4">
                    <label for="link">Lien google map : </label>
                    <input type="text" class="form-control" name="link" id="link" placeholder="Entrez le lien">
                </div>
            </div>
        </div>
        
        <!-- <div class="form-group">
            <label>Images : </label><br>
            <input type="file" name="image[]" multiple>
        </div> -->

        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary" name="submit">Ajouter</button>
        </div>

    </form>
</div>

<?php
require_once "footerAdmin.php";