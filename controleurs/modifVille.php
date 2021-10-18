<?php
require_once "traitement.php";

if(
    !empty($_POST["libelle"]) && is_string($_POST["libelle"]) &&
    !empty($_POST["region"]) && is_numeric($_POST["region"]) && 
    !empty($_POST["latitude"]) && is_numeric($_POST["latitude"]) && 
    !empty($_POST["longitude"]) && is_numeric($_POST["longitude"])
    ){

        if(!empty($_POST["description"]) && is_string($_POST["description"]) ){
            $description = $_POST["description"];
        } else {
            $description = null;
        }

        // on vérifie que chaque fichier est bien une image;
        if(count($_FILES["file"]["tmp_name"]) > 1){
            foreach($_FILES["file"]["tmp_name"] as $image){
                if(!exif_imagetype($image)){
                    header("location:../admin/modifVille.php");
                }
            }
        }

        $Ville = new Ville($_GET["id"]);

    try{

        if($Ville->getUuid() == null){
            $nom_doss = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss) != false){
                $nom_doss = bin2hex(random_bytes(32));
            }
            mkdir("../src/uuid/".$nom_doss, 0700);
            $Ville->setUuid($nom_doss);
        }

        if(!empty($_FILES["banniere"])){
            $nameBan = "banniere";
            $target_dir = "../src/uuid/".$Ville->getUuid()."/";
            $imageFileType = strtolower(pathinfo($_FILES["banniere"]["name"],PATHINFO_EXTENSION));
            $target_file = $target_dir . $nameBan . "." . "png";
            // $check = getimagesize($_FILES["banniere"]["tmp_name"]);
            move_uploaded_file($_FILES["banniere"]["tmp_name"], $target_file);
        }

        if(count($_FILES["file"]["tmp_name"]) > 1){

            $repertoire = "../src/uuid/".$Ville->getUuid();
            if(is_dir($repertoire)){  
                if($iteration = opendir($repertoire)){  
                    while(($fichier = readdir($iteration)) !== false){  
                        if($fichier != "." && $fichier != ".."){
                            $fichier_info = finfo_open(FILEINFO_MIME_TYPE);
                            $mime_type = finfo_file($fichier_info, $repertoire."/".$fichier);
                            if(strpos($mime_type, 'image/') === 0){
                                $test = substr($fichier, strlen($_POST["libelle"]), strlen($_POST["libelle"]));
                                $pos = substr($test, 0, strrpos($test, ".")) + 1;
                            }
                        }  
                    }  
                    closedir($iteration);  
                }  
            }
            if(empty($pos)){
                $pos=0;
            }

            for($i=0; $i < count($_FILES["file"]["name"]); $i++){
                $newName = $_POST["libelle"].$pos;
                $target_dir = "../src/uuid/". $Ville->getUuid() ."/";
                $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
                $target_file = $target_dir . $newName . "." . "png";
                // $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
                move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
                $pos++;
            }
        }

        $Ville->updateVille($_POST["libelle"], $_POST["latitude"], $_POST["longitude"], $_POST["region"], $description, $Ville->getUuid(), $_GET["id"]);
        header("location:../admin/modifVille.php");
    }catch(exception $e){
        header("location:../admin/modifVille.php?error=crash");
    }
}else{
    header("location:../admin/modifVille.php?error=all");
}