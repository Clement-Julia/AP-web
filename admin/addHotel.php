<?php
require_once "headerAdmin.php";
$hebergement = new Hebergement();
$hotels = $hebergement->getAllHotel();

$villes = new Ville();
$infos_v = $villes->getAllville();

$options = new Option();
$infos_o = $options->getAllOption();
?>

<div class="container">
    <h1 class="mb-3">Ajout d'un hébergement :</h1>
    <form method="POST" action="../controleurs/addHotel.php" enctype="multipart/form-data">

        <div class="form-group">
            <label for="libelle">Nom : </label>
            <input type="text" class="form-control" name="libelle" id="name" placeholder="Entrez le nom d'un hébergement" required>
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
            <input type="number" class="form-control" name="prix" id="prix" placeholder="Entrez le prix d'une nuit" required>
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

        <div class="form-group mt-4">
            <input type="file" name="file[]" id="file" class="inputfile inputfile-1 d-none" data-multiple-caption="{count} fichiers" multiple />
            <label for="file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Choisissez une image&hellip;</span></label>
        </div>

        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary">Créer</button>
        </div>

    </form>
</div>


<?php
require_once "footerAdmin.php";