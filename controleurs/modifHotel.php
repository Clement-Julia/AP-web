<?php
require_once "traitement.php";

if(
    !empty($_POST["libelle"]) && is_string($_POST["libelle"]) &&
    !empty($_POST["ville"]) && is_numeric($_POST["ville"]) && 
    !empty($_POST["description"]) && is_string($_POST["description"]) &&
    !empty($_POST["prix"]) && is_numeric($_POST["prix"]) && 
    !empty($_POST["latitude"]) && is_numeric($_POST["latitude"]) && 
    !empty($_POST["longitude"]) && is_numeric($_POST["longitude"]) && 
    !empty($_POST["adresse"]) && is_string($_POST["adresse"])
){
    // on vérifie que chaque option est bien un nombre
    foreach($_POST["options"] as $key => $value){
        if(!is_numeric($value)){
            header("location:../admin/modifHotel.php");
        }
    }

    // on vérifie les ext des images 
    for($i=0; $i < count($_FILES["file"]["name"]); $i++){
        $ext = substr($_FILES["file"]["name"][$i], strrpos($_FILES["file"]["name"][$i], '.'));
        if(strtolower($ext) != ".png" && strtolower($ext) != ".jpeg" && strtolower($ext) != ".jpg"){
            $error = true;
        }
    }

    $Hotel = new Hebergement($_GET["id"]);

    if($error){
        try{
            $repertoire = "../assets/src/uuid/".$Hotel->getUuid();
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
    
            if($Hotel->getUuid() == null){
                $nom_doss = bin2hex(random_bytes(32));
                while(file_exists("../assets/src/uuid/".$nom_doss) != false){
                    $nom_doss = bin2hex(random_bytes(32));
                }
                mkdir("../assets/src/uuid/".$nom_doss, 0700);
                $Hotel->setUuid($nom_doss);
            }
    
            if(!empty($_FILES["banniere"])){
                $nameBan = "banniere";
                $target_dir = "../assets/src/uuid/".$Hotel->getUuid()."/";
                $imageFileType = strtolower(pathinfo($_FILES["banniere"]["name"],PATHINFO_EXTENSION));
                $target_file = $target_dir . $nameBan . "." . "png";
                $check = getimagesize($_FILES["banniere"]["tmp_name"]);
                move_uploaded_file($_FILES["banniere"]["tmp_name"], $target_file);
            }
    
            if(!empty($_FILES["file"])){
                for($i=0; $i < (count($_FILES["file"]["name"])); $i++){
                    $newName = $_POST["libelle"].$pos;
                    $target_dir = "../assets/src/uuid/". $Hotel->getUuid() ."/";
                    $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
                    $target_file = $target_dir . $newName . "." . "png";
                    // $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
                    move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
                    $pos++;
                }
            }
    
            $option = new Option();
            $option->supOptions($_GET["id"]);
            $option->addOptions($_GET["id"], $_POST["options"]);
            $Hotel->updateHotel($_POST["libelle"], $_POST["description"], $_POST["ville"], $_POST["latitude"], $_POST["adresse"], $_POST["longitude"], $_POST["prix"], $Hotel->getUuid(), $_GET["id"]);
    
            header("location:../admin/modifHotel.php?success");
        }catch(exception $e){
            header("location:../admin/modifHotel.php?error=crash");
        }
    }else{
        header("location:../admin/modifHotel.php?error=ext");
    }
}else{
    header("location:../admin/modifHotel.php?error=all");
}