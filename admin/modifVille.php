<?php
require_once "headerAdmin.php";
$region = new Region();
$infos = $region->getAllregion();
?>

<div class="container">
    <h1 class="mb-3">Modification d'une ville :</h1>
    <?php
    if(empty($_GET)){
        $villes = new Ville();
        $infos = $villes->getAllville();
        ?>
            <form method="GET" action="modifVille.php">

                <div class="form-group text-center">
                    <label for="libelle">Nom : </label>
                    <input class="form-control" list="datalistOptions" name="libelle" id="exampleDataList" placeholder="Entrez le nom de la ville à modifier" required autocomplete="off">
                    <datalist id="datalistOptions">
                        <?php
                            foreach($infos as $info){
                                ?>
                                    <option value="<?= $info["libelle"] ?>"></option>
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
        $villes = new Ville();
        $info_ville = $villes->getVillebyName($_GET["libelle"]);
        ?>
        <form method="POST" action="../controleurs/modifVille.php?id=<?= $info_ville["idVille"] ?>">
            <div class="form-group">
                <label for="libelle">Nom : </label>
                <input type="text" class="form-control" name="libelle" id="libelle" placeholder="Entrez le nom d'une ville" required autocomplete="off" value="<?= $info_ville["libelle"] ?>">
            </div>

            <div class="form-group">
                <label for="region">Appartenance : </label>
                <select class="custom-select" aria-label="Default select example" name="region" required>
                    <option selected disabled>Selectionnez le nom de la région</option>
                    <?php
                        foreach($infos as $info){
                            ?>
                                <option value="<?= $info["idRegion"] ?>" <?= ($info["idRegion"] == $info_ville["idRegion"]) ? "selected" : "" ?>><?= $info["libelle"] ?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description : </label>
                <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Entrez la description de la ville"><?= $info_ville["description"] ?></textarea>
            </div>

            <div class="form-group mt-4">
                <label for="latitude">Latitude : </label>
                <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Entrez une latitude" autocomplete="off" value="<?= $info_ville["latitude"] ?>">
            </div>

            <div class="form-group">
                <label for="longitude">Longitude : </label>
                <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Entrez une longitude" autocomplete="off" value="<?= $info_ville["longitude"] ?>">
            </div>

            <div class="form-group text-center mt-4">
                <button type="submit" class="btn btn-warning">Modifier</button>

                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">
                Supprimer
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                Vous êtes sur le point de supprimer <?=$_GET["libelle"]?>
                                <br> Êtes-vous sûr ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                                <a href="../controleurs/supVille.php?libelle=<?=$_GET["libelle"]?>"><button type="button" class="btn btn-danger">Oui</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <?php
    }
    ?>
</div>


<?php
require_once "footerAdmin.php";