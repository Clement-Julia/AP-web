<?php
require_once "headerAdmin.php";

$villes = new Ville();
$infos_v = $villes->getAllville();

$options = new Option();
$infos_o = $options->getAllOption();

$users = new Utilisateur();
$info_u = $users->getAllUsers();
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
    if(!empty($_GET["error"]) && $_GET["error"] == "ext"){
        ?>
        <div class="container alert alert-danger">
            <p>
                Les seules extension autorisée pour les images sont : .png, .jpeg et .jpg
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
    <h1 class="mb-3">Ajout d'un hébergement :</h1>
    <form method="POST" action="../controleurs/addHotel.php" enctype="multipart/form-data">

        <div class="form-group">
            <label for="proprio">Propriétaire : </label>
            <input class="form-control" list="datalistOptions" name="proprio" id="exampleDataList" placeholder="Entrez le nom du propriétaire de l'hébergement" required autocomplete="off" required>
            <datalist id="datalistOptions">
                <?php
                    foreach($info_u as $info){
                        ?>
                            <option value="<?= $info["idUtilisateur"] ?>"><?= $info["nom"] . " " . $info["prenom"] ?></option>
                        <?php
                    }
                ?>
            </datalist>
        </div>


        <div class="form-group">
            <label for="libelle">Nom : </label>
            <input type="text" class="form-control" name="libelle" id="libelle" placeholder="Entrez le nom d'un hébergement" required>
        </div>

        <div class="form-group">
            <label for="description">Description : </label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Entrez la description de l'hébergement"></textarea>
        </div>

        <div class="form-group">
            <label for="ville">Appartenance : </label>
            <select class="custom-select" aria-label="Default select example" name="ville" required>
                <option selected disabled>Selectionnez le nom de la ville</option>
                <?php
                    foreach($infos_v as $info){
                        ?>
                            <option value="<?= $info["idVille"] ?>"><?= $info["libelle"] ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="prix">Prix : </label>
            <input type="number" class="form-control" name="prix" id="prix" step=".01" placeholder="Entrez le prix d'une nuit" required>
        </div>

        <div class="my-4">
            <label>Extras : </label>
            <?php
            $i = 1;
            foreach($infos_o as $info){
                ?>
                <div class="custom-control custom-switch mx-2">
                    <input type="checkbox" class="custom-control-input" name="options[]" id="customSwitch<?=$i?>" value=<?= $info["idOption"]?>>
                    <label class="custom-control-label mb-1" for="customSwitch<?=$i?>"><?= $info["libelle"] ?></label>
                </div>
                <?php
                $i++;
            }
            ?>
        </div>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-link text-muted active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Via coordonnées</a>
                <a class="nav-link text-muted" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Via lien google map</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade active show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
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

        <div class="form-group">
            <label for="adresse">Adresse : </label>
            <input type="text" class="form-control" name="adresse" id="adresse" placeholder="Entrez l'adresse de l'hébergement" required>
        </div>

        <div class="form-group mt-4">
            <input type="file" name="file[]" id="file" class="inputfile inputfile-1 d-none" data-multiple-caption="{count} fichiers" accept=".png, .jpeg, .jpg" multiple />
            <label for="file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choisissez une image&hellip;</span></label>

            <input type="file" name="banniere" id="banniere" accept=".png, .jpeg, .jpg" class="inputfile inputfile-1 d-none">
            <label for="banniere"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choisissez une bannière&hellip;</span></label>
        </div>

        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary">Créer</button>
        </div>

    </form>
</div>


<?php
require_once "footerAdmin.php";