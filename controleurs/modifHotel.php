<?php
require_once "traitement.php";

if(
    !empty($_POST["libelle"]) && is_string($_POST["libelle"]) &&
    !empty($_POST["ville"]) && is_numeric($_POST["ville"]) && 
    !empty($_POST["description"]) && is_string($_POST["description"]) &&
    !empty($_POST["prix"]) && is_numeric($_POST["prix"]) && 
    !empty($_POST["latitude"]) && is_numeric($_POST["latitude"]) && 
    !empty($_POST["longitude"]) && is_numeric($_POST["longitude"])
    ){
        // on vérifie que chaque option est bien un nombre
        foreach($_POST["options"] as $key => $value){
            if(!is_numeric($value)){
                header("location:../admin/modifHotel.php");
            }
        }
        // on vérifie que chaque fichier est bien une image
        foreach($_FILES["file"]["tmp_name"] as $image){
            if(!exif_imagetype($image)){
                header("location:../admin/modifHotel.php");
            }
        }

        $Hotel = new Hebergement($_GET["id"]);

    try{

        if($Hotel->getUuid() == null){
            $nom_doss = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss) != false){
                $nom_doss = bin2hex(random_bytes(32));
            }
            mkdir("../src/uuid/".$nom_doss, 0700);
            $Hotel->setUuid($nom_doss);
        }
        
        if(!empty($_FILES["file"])){
            for($i=0; $i < count($_FILES["file"]["name"]); $i++){
                $newName = $_POST["libelle"].$i;
                $target_dir = "../src/uuid/". $Hotel->getUuid() ."/";
                $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
                $target_file = $target_dir . $newName . "." . "png";
                // $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
                move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
            }
        }

        $option = new Option();
        $option->supOptions($_GET["id"]);
        $option->addOptions($_GET["id"], $_POST["options"]);
        $Hotel->updateHotel($_POST["libelle"], $_POST["description"], $_POST["ville"], $_POST["latitude"], $_POST["longitude"], $_POST["prix"], $Hotel->getUuid(), $_GET["id"]);

        header("location:../admin/modifHotel.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}