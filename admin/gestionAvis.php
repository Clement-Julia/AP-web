<?php
require_once "headerAdmin.php";

$avis = new Avis();
// print_r($alls)
?>

<div class="container">
    <?php
    if(empty($_GET)){
        $hotels = new Hebergement();
        $infos = $hotels->getAllhotel();
        ?>
        <h1 class="mb-3">Modification des avis :</h1>        
            <form method="GET" action="gestionAvis.php">

                <div class="form-group text-center">
                    <label for="libelle">Nom : </label>
                    <input class="form-control" list="datalistOptions" name="libelle" id="exampleDataList" placeholder="Entrez le nom de l'hebergement à modifier" required autocomplete="off">
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
        $alls = $avis->getAllAvisByHebergement($_GET["libelle"]);

        if(!empty($alls)){
            ?>
            <h1 class="mb-5">Modification des avis de <?= $_GET["libelle"] ?> :</h1>
            <div class="row">
                <?php
                $i = 0;
                foreach($alls as $all){
                    ?>
                    <div class="card text-center mr-3" style="width: 18rem;">
                        <div class="card-header">
                            <h5 class="card-title"><?=$all["nom"] ." ". $all["prenom"]?></h5>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?=$all["note"]?><i class="fas fa-star" style="color: #f2f200;"></i></p>
                            <p class="card-text"><?=$all["commentaire"]?></p>
                            <a href="../controleurs/modifAvis.php?status=delete&admin=true&id=<?= $all["idHebergement"] ?>" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                    <?php
                    $i++;
                }
                ?>
            </div>
            <a href="gestionAvis.php" class="btn btn-secondary mt-5"><i class="fas fa-arrow-left"></i></a>
            <?php
        }else{
            ?>
            <h1 class="mb-5">Modification des avis de <?= $_GET["libelle"] ?> :</h1>
            <h3 class="text-muted">L'hebergement n'a aucun avis :(</h3>
            <a href="gestionAvis.php" class="btn btn-secondary mt-5"><i class="fas fa-arrow-left"></i></a>
            <?php
        }
    }
    ?>
</div>

<?php
require_once "footerAdmin.php";