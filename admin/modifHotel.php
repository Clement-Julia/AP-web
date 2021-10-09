<?php
require_once "headerAdmin.php";
$villes = new Ville();
$infos = $villes->getAllville();

$options = new Option();
$infos_o = $options->getAllOption();
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
                <?php
                $infos_c = $options->getOptionChecked($info_hotel["idHebergement"]);
                $i = 1;
                $tab = [];
                foreach($infos_c as $checked){
                    $tab[] = $checked["idOption"];
                }
                foreach($infos_o as $info){
                    ?>
                    <div class="custom-control custom-switch mx-2">
                        <input type="checkbox" class="custom-control-input" name="options[]" id="customSwitch<?=$i?>" value=<?= $info["idOption"]?> <?= (in_array($info["idOption"], $tab)) ? "checked" : "" ?>>
                        <label class="custom-control-label mb-1" for="customSwitch<?=$i?>"><?= $info["libelle"] ?></label>
                    </div>
                    <?php
                    $i++;
                }
                ?>
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
                                <a href="../controleurs/supHotel.php?libelle=<?=$_GET["libelle"]?>"><button type="button" class="btn btn-danger">Oui</button></a>
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