<?php
require_once "headerAdmin.php";

$region = new Region();
$infos_region = $region->getAllregion();

$villes = new Ville();
$infos = $villes->getAllville();

if(!empty($_GET["libelle"])){
    $verif = 0;
    foreach($infos as $info){
        if($info["libelle"] == $_GET["libelle"]){
            $verif = 1;
        }
    }
    if($verif == 0){
        $_GET["libelle"] = "error";
    }
    if($_GET["libelle"] == "error"){
        ?>
        <div class="container alert alert-danger">
            <p>
                La ville n'existe pas
            </p>
        </div>
        <?php
    }   
}
$bool = 0;
?>

<div class="container">
    <h1 class="mb-3">Modification d'une ville :</h1>
    <?php
    if(empty($_GET) || $_GET["libelle"] == "error"){
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
        $info_ville = $villes->getVillebyName($_GET["libelle"]);
        ?>
        <form method="POST" action="../controleurs/modifVille.php?id=<?= $info_ville["idVille"] ?>"  multipart="" enctype="multipart/form-data">
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

            <div class="form-group mt-4">
                <input type="file" name="file[]" id="file" class="inputfile inputfile-1 d-none" data-multiple-caption="{count} fichiers" multiple />
                <label for="file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Ajouter une image&hellip;</span></label>

                <?php
                    if($bool == 0){
                        ?>
                        <input type="file" name="banniere" id="banniere" class="inputfile inputfile-1 d-none">
                        <label for="banniere"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choisissez une bannière&hellip;</span></label>
                        <?php
                    }
                ?>
            </div>
            
            
            <div class="form-group">
                <label>Images :</label>
                <div id="image">
                    <?php
                        lister_images("../src/uuid/".$info_ville["uuid"]);
                    ?>
                </div>
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