<?php
require_once "traitement.php";
$ville = new Ville();
print_r($_POST) ;exit;
echo $_POST["latitude"];
echo $_POST["longitude"];
echo $_POST["link"];exit;

if(!empty($_POST["latitude"]) && !empty($_POST["longitude"]) && empty($_POST["link"])){
    try{

        //Création du dossier
        $nom_doss = bin2hex(random_bytes(32));
        if(file_exists($nom_doss) == false){
            mkdir("../src/".$nom_doss, 0700);
        }

        //Création du(es) fichier(s)
        for($i=0; $i < count($_FILES["file"]) - 1; $i++){
            $newName = $_POST["libelle"].$i;
            $target_dir = "../src/".$nom_doss."/";
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
            $target_file = $target_dir . $newName . "." . "png";
            $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
            move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
        }

        $ville->addVille($_POST["libelle"], $_POST["latitude"], $_POST["longitude"], $_POST["region"], $_POST["description"], $nom_doss);
        header("location:../admin/addVille.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}elseif(empty($_POST["latitude"]) && empty($_POST["longitude"]) && !empty($_POST["link"])){
    try{

        $latitude = substr($_POST["link"], strpos($_POST["link"], "3d") + 2);
        $latitude = substr($latitude, 0, 5 - strlen($latitude));

        $longitude = substr($_POST["link"], strpos($_POST["link"], "4d") + 2);
        $longitude = substr($longitude, 0, 4 - strlen($latitude) - 3);

        //Création du dossier
        $nom_doss = bin2hex(random_bytes(32));
        if(file_exists($nom_doss) == false){
            mkdir("../src/".$nom_doss, 0700);
        }

        //Création du(es) fichier(s)
        for($i=0; $i < count($_FILES["file"]) - 1; $i++){
            $newName = $_POST["libelle"].$i;
            $target_dir = "../src/".$nom_doss."/";
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
            $target_file = $target_dir . $newName . "." . "png";
            $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
            move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
        }

        $ville->addVille($_POST["libelle"], $latitude, $longitude, $_POST["region"], $_POST["description"], $nom_doss);
        header("location:../admin/addVille.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}