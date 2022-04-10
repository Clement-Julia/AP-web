<?php
require_once "headerAdmin.php";

$hotels = new Hebergement();
$infos = $hotels->getAllhotel();

$options = new Option();
$infos_o = $options->getAllOption();

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
                L'hebergement n'existe pas
            </p>
        </div>
        <?php
    }
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
            L'hébergement a bien été modifié !
        </p>
    </div>
    <?php
}
?>

<div class="container mb-2">
    <h1 class="mb-3">Modification d'un hébergement :</h1>
    <?php
    if(empty($_GET) || !empty($_GET["libelle"]) && $_GET["libelle"] == "error" || isset($_GET["success"])){
        ?>
            <form method="GET" action="modifHotel.php">

                <div class="form-group text-center">
                    <label for="id">Hébergement  : </label>
                    <select class="selectpicker" name="libelle" data-live-search="true" data-width="100%" title="Choisissez un hébergement">
                        <?php
                            foreach($infos as $info){
                                ?>
                                    <option value="<?= htmlspecialchars($info["libelle"], ENT_QUOTES) ?>"><?= htmlspecialchars($info["libelle"], ENT_QUOTES) ?></option>
                                <?php
                            }
                        ?>
                    </select>
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

        $villes = new Ville();
        $infos = $villes->getAllville();
        $hotels = new Hebergement();
        $info_hotel = $hotels->getHotelbyName($_GET["libelle"]);

        $_SESSION["supHotel"] = $info_hotel["idHebergement"];
        ?>
        <form method="POST" action="../controleurs/modifHotel.php?id=<?= $info_hotel["idHebergement"] ?>" multipart="" enctype="multipart/form-data">

            <div class="form-group">
                <label for="libelle">Nom : </label>
                <input type="text" class="form-control" name="libelle" id="libelle" placeholder="Entrez le nom d'une ville" required autocomplete="off" value="<?= htmlspecialchars($info_hotel["libelle"], ENT_QUOTES) ?>">
            </div>

            <div class="form-group">
                <label for="ville">Appartenance : </label>
                <select class="custom-select" aria-label="Default select example" name="ville" required>
                    <option selected disabled>Selectionnez le nom de la ville</option>
                    <?php
                        foreach($infos as $info){
                            ?>
                                <option value="<?= $info["idVille"] ?>" <?= ($info["idVille"] == $info_hotel["idVille"]) ? "selected" : "" ?>><?= htmlspecialchars($info["libelle"], ENT_QUOTES) ?></option>
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
                        <label class="custom-control-label mb-1" for="customSwitch<?=$i?>"><?= htmlspecialchars($info["libelle"], ENT_QUOTES) ?></label>
                    </div>
                    <?php
                    $i++;
                }
                ?>
            </div>

            <div class="form-group">
                <label for="prix">Prix : </label>
                <input type="number" class="form-control" name="prix" id="prix" step=".01" placeholder="Entrez le prix d'une nuit" value="<?= $info_hotel["prix"] ?>" required>
            </div>

            <div class="form-group mt-4">
                <label for="latitude">Latitude : </label>
                <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Entrez une latitude" autocomplete="off" value="<?= $info_hotel["latitude"] ?>">
            </div>

            <div class="form-group">
                <label for="longitude">Longitude : </label>
                <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Entrez une longitude" autocomplete="off" value="<?= $info_hotel["longitude"] ?>">
            </div>

            <div class="form-group">
                <label for="adresse">Adresse : </label>
                <input type="text" class="form-control" name="adresse" id="adresse" placeholder="Entrez l'adresse de l'hébergement" value="<?= $info_hotel["adresse"] ?>" required>
            </div>

            <div class="form-group mt-4">
                <input type="file" name="file[]" id="file" class="inputfile inputfile-1 d-none" data-multiple-caption="{count} fichiers" accept=".png, .jpeg, .jpg" multiple />
                <label for="file"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Ajouter une image&hellip;</span></label>

                <input type="file" name="banniere" id="banniere" accept=".png, .jpeg, .jpg" class="inputfile inputfile-1 d-none">
                <label for="banniere"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"/></svg> <span>Changez la bannière&hellip;</span></label>
            </div>

            <div class="form-group">
                <label>Bannière :</label>
                <div id="banniere" class="d-flex">
                    <?php 
                        $filename = "../assets/src/uuid/" . $info_hotel["uuid"] . "/banniere.*";
                        $folder = scandir("../assets/src/uuid/".$info_hotel["uuid"]);
                        for($i = 2; $i < count($folder); $i++){
                            $ext = substr($folder[$i], strrpos($folder[$i], '.'));
                            if(strtok($folder[$i], '.') == "banniere"){
                                ?>
                                <img src="../assets/src/uuid/<?=$info_hotel["uuid"]?>/<?=$folder[$i]?>" name="banniere" class="img-fluid rounded badgetest <?= (!empty(glob($filename))) ? "" : "d-none" ?>" style="max-width: 300px">
                                <?php
                            }
                        }
                        ?>
                        <?php
                        if(empty(glob($filename))){
                            ?>
                                <span class="text-muted font-italic">L'hébergement n'a pas de bannière...</span>
                            <?php
                        }
                    ?>
                </div>
            </div>
            
            <div class="form-group">
                <label>Images :</label>
                <div id="image" class="d-flex">
                    <?php
                        $filename = "../assets/src/uuid/" . $info_hotel["uuid"] . "/banniere.*";
                        $img = scandir("../assets/src/uuid/".$info_hotel["uuid"]);
                        if(count($img) <= 2 || count($img) <= 3 && !empty(glob($filename))){
                            ?>
                                <span class="text-muted font-italic">Cet hébergement ne possède pas de photo...</span>
                            <?php
                        }else{
                            lister_images("../assets/src/uuid/".$info_hotel["uuid"]);
                        }
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
                                <a href="../controleurs/supHotel.php"><button type="button" class="btn btn-danger">Oui</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </form>
        <a href="modifHotel.php" class="btn btn-secondary mt-5"><i class="fas fa-arrow-left"></i></a>
        <?php
    }
    ?>
</div>


<?php
require_once "footerAdmin.php";