<?php
require_once "traitement.php";
$ville = new Ville($_POST["ville"]);
$hotel = new Hebergement();
$option = new Option();
$user = new Utilisateur();


if(!empty($_POST["latitude"]) && !empty($_POST["longitude"]) && empty($_POST["link"]) && !empty($_FILES["file"]) || empty($_POST["latitude"]) && empty($_POST["longitude"]) && !empty($_POST["link"]) && !empty($_FILES["file"])){
    try{

        if(!empty($_POST["link"])){
            $_POST["latitude"] = substr($_POST["link"], strpos($_POST["link"], "3d") + 2);
            $_POST["latitude"] = substr($_POST["latitude"], 0, 5 - strlen($_POST["latitude"]));
    
            $_POST["longitude"] = substr($_POST["link"], strpos($_POST["link"], "4d") + 2);
            $_POST["longitude"] = substr($_POST["longitude"], 0, 4 - strlen($_POST["latitude"]) - 3);
        }

        //Création du dossier
        $nom_doss = bin2hex(random_bytes(32));
        while(file_exists("../assets/src/uuid/".$nom_doss) != false){
            $nom_doss = bin2hex(random_bytes(32));
        }
        mkdir("../assets/src/uuid/".$nom_doss, 0700);

        if(!empty($_FILES["banniere"])){
            $nameBan = "banniere";
            $target_dir = "../assets/src/uuid/".$nom_doss."/";
            $imageFileType = strtolower(pathinfo($_FILES["banniere"]["name"],PATHINFO_EXTENSION));
            $target_file = $target_dir . $nameBan . "." . "png";
            $check = getimagesize($_FILES["banniere"]["tmp_name"]);
            move_uploaded_file($_FILES["banniere"]["tmp_name"], $target_file);
        }

        //Création du(es) fichier(s)
        for($i=0; $i < count($_FILES["file"]["name"]); $i++){
            $newName = $_POST["libelle"].$i;
            $target_dir = "../assets/src/uuid/".$nom_doss."/";
            $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
            $target_file = $target_dir . $newName . "." . "png";
            $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
            move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
        }

        $proprio = $user->get_proprio_by_name($_POST['proprio']);
        $hotel->addHotel($_POST["libelle"], $_POST["description"], $_POST["ville"], $_POST["latitude"], $_POST["longitude"], $_POST["prix"], $nom_doss, $proprio);
        $info = $hotel->getHotelbyName($_POST["libelle"]);
        $option->addOptions($info["idHebergement"], $_POST["options"]);
    
        header("location:../admin/addHotel.php");

    }catch(exception $e){
        header("location:../admin/addVille.php?error=crash");
    }
    
}else{
    header("location:../admin/addHotel.php?error=all");
}