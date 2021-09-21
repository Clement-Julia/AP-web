<?php
require_once "headerAdmin.php";
$villes = new Ville();
$infos = $villes->getAllville();
?>

<div class="container">
    <h1 class="mb-3">Modification d'un hébergement :</h1>
    <?php
    if(empty($_GET)){
        $hotels = new Hebergement();
        $infos = $hotels->getAllhotel();
        ?>
            <form method="GET" action="modifHotel.php">

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
        $hotels = new Hebergement();
        $info_hotel = $hotels->getHotelbyName($_GET["libelle"]);
        ?>
        <form method="POST" action="../controleurs/modifHotel.php?id=<?= $info_hotel["idHebergement"] ?>">
            <div class="form-group">
                <label for="libelle">Nom : </label>
                <input type="text" class="form-control" name="libelle" id="libelle" placeholder="Entrez le nom d'une ville" required autocomplete="off" value="<?= $info_hotel["libelle"] ?>">
            </div>

            <div class="form-group">
                <label for="ville">Appartenance : </label>
                <select class="custom-select" aria-label="Default select example" name="ville" required>
                    <option selected disabled>Selectionnez le nom de la ville</option>
                    <?php
                        foreach($infos as $info){
                            ?>
                                <option value="<?= $info["idVille"] ?>" <?= ($info["idVille"] == $info_hotel["idVille"]) ? "selected" : "" ?>><?= $info["libelle"] ?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Description : </label>
                <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Entrez la description de la ville"><?= $info_hotel["description"] ?></textarea>
            </div>

            <div class="my-4">
                <label>Extras : </label>
                <div class="custom-control custom-switch mx-2">
                    <input type="checkbox" class="custom-control-input" name="television" id="customSwitch1" <?= ($info_hotel["television"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch1">Télévision</label>
                    <br>
                </div>

                <div class="custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="lave_linge" id="customSwitch2" <?= ($info_hotel["lave_linge"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch2">Lave linge</label>
                </div>

                <div class="custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="seche_linge" id="customSwitch3" <?= ($info_hotel["seche_linge"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch3">Sèche ling</label>
                </div>

                <div class="custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="cuisine" id="customSwitch4" <?= ($info_hotel["cuisine"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch4">Cuisine</label>
                </div>

                <div class="custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="réfrigirateur" id="customSwitch5" <?= ($info_hotel["refrigirateur"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch5">Réfrigirateur</label>
                </div>

                <div class="custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="four" id="customSwitch6" <?= ($info_hotel["four"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch6">Four</label>
                </div>

                <div class="custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="parking" id="customSwitch7" <?= ($info_hotel["parking_gratuit"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch7">Parking</label>
                </div>

                <div class="custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="linge_de_maison" id="customSwitch8" <?= ($info_hotel["linge_de_maison"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch8">Linge de l'hotel</label>
                </div>

                <div class="custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="vaisselle" id="customSwitch9" <?= ($info_hotel["vaiselle"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch9">Vaisselle</label>
                </div>

                <div class="custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="cafetiere" id="customSwitch10" <?= ($info_hotel["cafetiere"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch10">Cafetière</label>
                </div>

                <div class = "custom-control custom-switch m-2">
                    <input type="checkbox" class="custom-control-input" name="climatisation" id="customSwitch11" <?= ($info_hotel["climatisation"] == 1) ? "checked" : "" ?>>
                    <label class="custom-control-label" for="customSwitch11">Climatisation</label>
                </div>

            </div>

            <div class="form-group">
                <label for="prix">Prix : </label>
                <input type="number" class="form-control" name="prix" id="prix" placeholder="Entrez le prix d'une nuit" value="<?= $info_hotel["prix"] ?>" required>
            </div>

            <div class="form-group mt-4">
                <label for="latitude">Latitude : </label>
                <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Entrez une latitude" autocomplete="off" value="<?= $info_hotel["latitude"] ?>">
            </div>

            <div class="form-group">
                <label for="longitude">Longitude : </label>
                <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Entrez une longitude" autocomplete="off" value="<?= $info_hotel["longitude"] ?>">
            </div>

            <div class="form-group text-center mt-4">
                <button type="submit" class="btn btn-warning">Modifier</button>
            </div>

        </form>
        <?php
    }
    ?>
</div>


<?php
require_once "footerAdmin.php";