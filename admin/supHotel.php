<?php
require_once "headerAdmin.php";
$hotels = new Hebergement();
$infos = $hotels->getAllhotel();
?>

<div class="container">

    <h1 class="mb-3">Suppression d'un hébergement :</h1>

        <form method="GET" action="../controleurs/supHotel.php">

            <div class="form-group text-center">
                <label for="libelle">Nom : </label>
                <input class="form-control" list="datalistOptions" name="libelle" id="exampleDataList" placeholder="Entrez le nom de la ville à supprimer" required autocomplete="off">
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
                <button type="submit" class="btn btn-danger">Supprimer</button>
            </div>

        </form>
</div>

<?php
require_once "footerAdmin.php";