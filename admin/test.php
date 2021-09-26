<?php
require_once "headerAdmin.php";

//Création du dossier
// $nom_doss = bin2hex(random_bytes(32));
// if(file_exists($nom_doss) == false){
//     mkdir("../src/".$nom_doss, 0700);
// }

// //Création du(es) fichier(s)
// for($i=0; $i < count($_FILES["file"]) - 1; $i++){
//     $newName = $_POST["libelle"].$i;
//     $target_dir = "../src/".$nom_doss."/";
//     $uploadOk = 1;
//     $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
//     $target_file = $target_dir . $newName . "." . "png";
//     $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
//     move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
// }
print_r($_POST);
?>

<div class="container">

<h1>Test :</h1>
    <form method="POST" action="test.php" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="libelle">Nom : </label>
            <input type="text" class="form-control" name="libelle" id="libelle" placeholder="Entrez le nom d'une ville" required autocomplete="off">
        </div>

        <div class="form-group">
            <label>Images : </label><br>
            <input type="file" name="file[]" multiple>
        </div>

        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary">Test</button>
        </div>

    </form>
</div>

<?php

require_once "footerAdmin.php";