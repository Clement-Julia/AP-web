<?php
require_once "headerAdmin.php";

$activites = new Activite();
$infos = $activites->getAllActiviteByVille();

$villes = new Ville();
$infos_v = $villes->getAllville();

$activités = new Activite();
$infos_a = $activités->getAllActivite();

if(!empty($_GET["activite"])){
    $verif = 0;
    foreach($infos as $info){
        if($info["description"] == $_GET["activite"]){
            $verif = 1;
        }
    }
    if($verif == 0){
        $_GET["activite"] = "error";
    }
    if($_GET["activite"] == "error"){
        ?>
        <div class="container alert alert-danger">
            <p>
                L'activité n'existe pas
            </p>
        </div>
        <?php
    }   
}
if(isset($_GET["success"])){
    ?>
    <div class="container alert alert-success">
        <p>
            L'activité a bien été modifiée !
        </p>
    </div>
    <?php
}
?>

<div class="container">
    <h1 class="mb-3">Modification d'une activité:</h1>
    <?php
    if(!empty($_GET["activite"]) && $_GET["activite"] == "error"){
        ?>
            <form method="GET" action="modifActivite.php">

                <div class="form-group">
                    <div id="ville">
                        <label for="DataListVille">Ville : </label>
                        <input class="form-control" list="datalistVilles" id="DataListVille" placeholder="Entrez le nom de l'activité à modifier" required autocomplete="off">
                        <datalist id="datalistVilles">
                            <?php
                                foreach($infos_v as $info){
                                    ?>
                                        <option value="<?= htmlspecialchars($info["libelle"], ENT_QUOTES) ?>"></option>
                                    <?php
                                }
                            ?>
                        </datalist>
                    </div>
                    
                    <div id="activite" class="mt-5">
                        <label for="DataListActivites">Activité : </label>
                        <input class="form-control" list="datalistActivites" name="activite" id="DataListActivite" placeholder="Entrez le nom de l'activité à modifier" required autocomplete="off">
                        <datalist id="datalistActivites">
                        </datalist>
                    </div>
                </div>

                <div class="form-group text-center mt-4">
                    <button type="submit" class="btn btn-warning">Modifier</button>
                </div>

            </form>
        
        <?php
    }else{
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
        $activite = new Activite();
        $activite = $activite->getActiviteForVilleByName($_GET["activite"]);

        $ville = new Ville($activite["idVille"]);

        $typeActivite = new Activite($activite["idActivite"]);
        ?>

        <form method="POST" action="../controleurs/modifActivite.php" enctype="multipart/form-data">

            <div class="form-group mt-4">
                <label for="libelle">Ville : </label>
                <input class="form-control" list="datalistVille" name="ville" id="DataListVille" placeholder="Entrez la ville où est pratiquée l'activité" value="<?= $ville->getLibelle() ?>" required autocomplete="off">
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
                <input class="form-control" list="datalistActivite" name="activite" id="DataListActivite" placeholder="Entrez le type d'activité" value="<?= $typeActivite->getLibelle() ?>" required autocomplete="off">
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
                <textarea class="form-control" name="description" id="description" cols="30" rows="10"><?= $activite["description"] ?></textarea>
                <input type="hidden" name="oldDescription" value="<?= $activite["description"] ?>">
            </div>
            <div class="form-group row mt-5">
                <div class="col-6 d-flex flex-column justify-content-center">

                    <div class="form-group">
                        <input type="hidden" name="oldLatitude" value="<?= $activite["latitude"] ?>">
                        <input type="hidden" name="oldLongitude" value="<?= $activite["longitude"] ?>">
                    </div>

                    <div class="form-group">
                        <label for="latitude">Latitude : </label>
                        <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Clickez sur la map pour la définir" value="<?= $activite["latitude"] ?>" autocomplete="off" disabled>
                        <input type="hidden" id="currentLatitude" name="currentLatitude" value="<?= $activite["latitude"] ?>">
                    </div>

                    <div class="form-group">
                        <label for="longitude">Longitude : </label>
                        <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Clickez sur la map pour la définir" value="<?= $activite["longitude"] ?>" autocomplete="off" disabled>
                        <input type="hidden" id="currentLongitude" name="currentLongitude" value="<?= $activite["longitude"] ?>">
                    </div>
                </div>
                <div class="col-6" id="map"></div>
            </div>
            <div class="form-group text-center mt-4">
                <button type="submit" class="btn btn-warning">Modifier</button>
            </div>

        </form>
        <a href="modifActivite.php" class="btn btn-secondary my-5"><i class="fas fa-arrow-left"></i></a>
        <?php

        ?>
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="js/map.js"></script>
        <?php
    }
    ?>
</div>

<script src="js/activite.js"></script>

<?php
require_once "footerAdmin.php";