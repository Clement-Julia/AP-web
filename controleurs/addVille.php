<?php
require_once "traitement.php";
$ville = new Ville();

if(!empty($_POST["latitude"]) && is_numeric($_POST["latitude"]) && !empty($_POST["longitude"]) && is_numeric($_POST["longitude"]) && empty($_POST["link"]) && !empty($_POST["cp"]) && count($_FILES["file"]) > 0 || empty($_POST["latitude"]) && is_numeric($_POST["latitude"]) && empty($_POST["longitude"]) && is_numeric($_POST["longitude"]) && !empty($_POST["link"]) && !empty($_POST["cp"]) && count($_FILES["file"]) > 0){
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
            header("location:../admin/addVille.php?error=link");
        }
    }

    if(!$error){
        try{

            if(!empty($_POST["link"])){
                $_POST["latitude"] = substr($_POST["link"], strpos($_POST["link"], "3d") + 2);
                $_POST["latitude"] = substr($_POST["latitude"], 0, 5 - strlen($_POST["latitude"]));
        
                $_POST["longitude"] = substr($_POST["link"], strpos($_POST["link"], "4d") + 2);
                $_POST["longitude"] = substr($_POST["longitude"], 0, 4 - strlen($_POST["latitude"]) - 3);
            }

            //Création du dossier
            $nom_doss = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss) != false){
                $nom_doss = bin2hex(random_bytes(32));
            }
            mkdir("../assets/src/uuid/".$nom_doss, 0700);

            if(!$_FILES["banniere"]["error"][0]){
                $nameBan = "banniere";
                $ext = substr($_FILES["banniere"]["name"], strrpos($_FILES["banniere"]["name"], '.'));
                $target_dir = "../assets/src/uuid/".$nom_doss."/";
                $imageFileType = strtolower(pathinfo($_FILES["banniere"]["name"],PATHINFO_EXTENSION));
                $target_file = $target_dir . $nameBan . $ext;
                $check = getimagesize($_FILES["banniere"]["tmp_name"]);
                move_uploaded_file($_FILES["banniere"]["tmp_name"], $target_file);
            }

            //Création du(es) fichier(s)
            if(!$_FILES["file"]["error"][0]){
                for($i=0; $i < count($_FILES["file"]["name"]); $i++){
                    $newName = $_POST["libelle"].$i;
                    $ext = substr($_FILES["file"]["name"][$i], strrpos($_FILES["file"]["name"][$i], '.'));
                    $target_dir = "../assets/src/uuid/".$nom_doss."/";
                    $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
                    $target_file = $target_dir . $newName . $ext;
                    $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
                    move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
                }
            }
            
            $ville->addVille($_POST["libelle"], $_POST["latitude"], $_POST["longitude"], $_POST["cp"], $_POST["region"], $_POST["description"], $nom_doss);
            header("location:../admin/addVille.php?success");
        }catch(exception $e){
            header("location:../admin/addVille.php?error=crash");
        }
    }else{
        header("location:../admin/addVille.php?error=ext");
    }
}else{
    header("location:../admin/addVille.php?error=all");
}