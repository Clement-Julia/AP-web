<?php
require_once "headerAdmin.php";
$hebergement = new Hebergement();
$hotels = $hebergement->getAllHotel();

$villes = new Ville();
$infos = $villes->getAllville();
?>


<div class="container">
    <h1 class="mb-3">Ajout d'un hébergement :</h1>
    <form method="POST" action="../controleurs/addHotel.php" enctype="multipart/form-data">

        <div class="form-group">
            <label for="name">Nom : </label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Entrez le nom d'un hébergement" required>
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
                    foreach($infos as $info){
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
            <div class="custom-control custom-switch mx-2">
                <input type="checkbox" class="custom-control-input" name="television" id="customSwitch1">
                <label class="custom-control-label" for="customSwitch1">Télévision</label>
                <br>
            </div>

            <div class="custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="lave_linge" id="customSwitch2">
                <label class="custom-control-label" for="customSwitch2">Lave linge</label>
            </div>

            <div class="custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="seche_linge" id="customSwitch3">
                <label class="custom-control-label" for="customSwitch3">Sèche ling</label>
            </div>

            <div class="custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="cuisine" id="customSwitch4">
                <label class="custom-control-label" for="customSwitch4">Cuisine</label>
            </div>

            <div class="custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="réfrigirateur" id="customSwitch5">
                <label class="custom-control-label" for="customSwitch5">Réfrigirateur</label>
            </div>

            <div class="custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="four" id="customSwitch6">
                <label class="custom-control-label" for="customSwitch6">Four</label>
            </div>

            <div class="custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="parking" id="customSwitch7">
                <label class="custom-control-label" for="customSwitch7">Parking</label>
            </div>

            <div class="custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="linge_de_maison" id="customSwitch8">
                <label class="custom-control-label" for="customSwitch8">Linge de l'hotel</label>
            </div>

            <div class="custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="vaisselle" id="customSwitch9">
                <label class="custom-control-label" for="customSwitch9">Vaisselle</label>
            </div>

            <div class="custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="cafetiere" id="customSwitch10">
                <label class="custom-control-label" for="customSwitch10">Cafetière</label>
            </div>

            <div class = "custom-control custom-switch m-2">
                <input type="checkbox" class="custom-control-input" name="climatisation" id="customSwitch11">
                <label class="custom-control-label" for="customSwitch11">Climatisation</label>
            </div>

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

        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary">Créer</button>
        </div>

    </form>
</div>


<?php
require_once "footerAdmin.php";