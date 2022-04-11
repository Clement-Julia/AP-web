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
    if(!empty($_POST["options"])){
        foreach($_POST["options"] as $key => $value){
            if(!is_numeric($value)){
                header("location:../admin/modifHotel.php");
            }
        }
    }

    $error = false;

    // on vérifie les ext des images 
    if($_FILES['file']['error'][0] != 4){
        for($i=0; $i < count($_FILES["file"]["name"]); $i++){
            $ext = substr($_FILES["file"]["name"][$i], strrpos($_FILES["file"]["name"][$i], '.'));
            if(strtolower($ext) != ".png" && strtolower($ext) != ".jpeg" && strtolower($ext) != ".jpg"){
                $error = true;
            }
        }
    }

    $Hotel = new Hebergement($_GET["id"]);

    if(!$error){

        if($Hotel->getUuid() == null){
            $nom_doss = bin2hex(random_bytes(32));
            while(file_exists("../assets/src/uuid/".$nom_doss) != false){
                $nom_doss = bin2hex(random_bytes(32));
            }
            mkdir("../assets/src/uuid/".$nom_doss, 0700);
            $Hotel->setUuid($nom_doss);
        }

        $folder = scandir("../assets/src/uuid/".$Hotel->getUuid());
        $pos = 1;
        for($i = 2; $i < count($folder); $i++){
            $ext = substr($folder[$i], strrpos($folder[$i], '.'));
            if(strtok($folder[$i], '.') != "banniere"){
                $pos++;
            }
        }

        if($_FILES['banniere']['error'] != 4){
            for($i = 2; $i < count($folder); $i++){
                if(strtok($folder[$i], '.') == "banniere"){
                    unlink("../assets/src/uuid/".$Hotel->getUuid()."/".$folder[$i]);
                }
            }

            $nameBan = "banniere";
            $ext = substr($_FILES["banniere"]["name"], strrpos($_FILES["banniere"]["name"], '.'));
            $target_dir = "../assets/src/uuid/".$Hotel->getUuid()."/";
            $imageFileType = strtolower(pathinfo($_FILES["banniere"]["name"],PATHINFO_EXTENSION));
            $target_file = $target_dir . $nameBan . $ext;
            move_uploaded_file($_FILES["banniere"]["tmp_name"], $target_file);
        }

        if($_FILES['file']['error'][0] != 4){
            for($i=0; $i < (count($_FILES["file"]["name"])); $i++){
                $newName = $_POST["libelle"].$pos;
                $ext = substr($_FILES["file"]["name"][$i], strrpos($_FILES["file"]["name"][$i], '.'));
                $target_dir = "../assets/src/uuid/". $Hotel->getUuid() ."/";
                $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
                $target_file = $target_dir . $newName . $ext;
                move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
                $pos++;
            }
        }

        try{
            if(!empty($_POST["options"])){
                $option = new Option();
                $option->supOptions($_GET["id"]);
                $option->addOptions($_GET["id"], $_POST["options"]);
            }
            $Hotel->updateHotel($_POST["libelle"], $_POST["description"], $_POST["ville"], $_POST["latitude"], $_POST["longitude"], $_POST["adresse"], $_POST["prix"], $Hotel->getUuid(), $_GET["id"]);
    
            header("location:../admin/modifHotel.php?success");
        }catch(exception $e){
            header("location:../admin/modifHotel.php?error=crash");
        }
    }else{
        header("location:../admin/modifHotel.php?libelle=".$_GET["libelle"]."&error=ext");
    }
}else{
    header("location:../admin/modifHotel.php?libelle=".$_GET["libelle"]."&error=all");
}