<?php
require_once "traitement.php";
$Ville = new Ville($_GET["id"]);

if(
    !empty($_POST["libelle"]) && is_string($_POST["libelle"]) &&
    !empty($_POST["region"]) && is_numeric($_POST["region"]) && 
    !empty($_POST["latitude"]) && is_numeric($_POST["latitude"]) && 
    !empty($_POST["longitude"]) && is_numeric($_POST["longitude"])&& 
    !empty($_POST["cp"]) && is_numeric($_POST["cp"])
){
    $error = false;

    if(!$_FILES["file"]["error"][0]){
        for($i=0; $i < count($_FILES["file"]["name"]); $i++){
            $ext = substr($_FILES["file"]["name"][$i], strrpos($_FILES["file"]["name"][$i], '.'));
            if(strtolower($ext) != ".png" && strtolower($ext) != ".jpeg" && strtolower($ext) != ".jpg"){
                $error = true;
            }
        }
    }
    if(!empty($_POST["link"])){
        $link = substr($_POST["link"], strpos($_POST["link"], "www."), 13);

        if($link != "www.google.com" && $link != "www.google.fr"){
            header("location:../admin/modifVille.php?error=link");
        }
    }

    if(!$error){

        // on vérifie que chaque fichier est bien une image;
        if(!$_FILES["file"]["error"][0]){
            foreach($_FILES["file"]["tmp_name"] as $image){
                if(!exif_imagetype($image)){
                    header("location:../admin/modifVille.php?error=file");
                }
            }
        }

        if($Ville->getUuid() == null){
            $nom_doss = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss) != false){
                $nom_doss = bin2hex(random_bytes(32));
            }
            mkdir("../src/uuid/".$nom_doss, 0700);
            $Ville->setUuid($nom_doss);
        }

        $folder = scandir("../assets/src/uuid/".$Ville->getUuid());
        $pos = 1;
        for($i = 2; $i < count($folder); $i++){
            $ext = substr($folder[$i], strrpos($folder[$i], '.'));
            if(strtok($folder[$i], '.') != "banniere"){
                $pos++;
            }
        }

        if(!$_FILES["banniere"]["error"][0]){

            for($i = 2; $i < count($folder); $i++){
                if(strtok($folder[$i], '.') == "banniere"){
                    unlink("../assets/src/uuid/".$Ville->getUuid()."/".$folder[$i]);
                }
            }

            $nameBan = "banniere";
            $ext = substr($_FILES["banniere"]["name"], strrpos($_FILES["banniere"]["name"], '.'));
            $target_dir = "../assets/src/uuid/".$Ville->getUuid()."/";
            $imageFileType = strtolower(pathinfo($_FILES["banniere"]["name"],PATHINFO_EXTENSION));
            $target_file = $target_dir . $nameBan . $ext;
            move_uploaded_file($_FILES["banniere"]["tmp_name"], $target_file);
        }

        if(!$_FILES["file"]["error"][0]){

            for($i=0; $i < count($_FILES["file"]["name"]); $i++){
                $newName = $_POST["libelle"].$pos;
                $ext = substr($_FILES["file"]["name"][$i], strrpos($_FILES["file"]["name"][$i], '.'));
                $target_dir = "../assets/src/uuid/". $Ville->getUuid() ."/";
                $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
                $target_file = $target_dir . $newName . $ext;
                move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
                $pos++;
            }
        }
        try{


            $Ville->updateVille($_POST["libelle"], $_POST["latitude"], $_POST["longitude"], $_POST["cp"], $_POST["region"], $_POST["description"], $Ville->getUuid(), $_GET["id"]);
            header("location:../admin/modifVille.php?success");
        }catch(exception $e){
            header("location:../admin/modifVille.php?error=crash");
        }
    }
}else{
    header("location:../admin/modifVille.php?error=all");
}