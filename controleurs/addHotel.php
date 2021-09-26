<?php
require_once "traitement.php";
$ville = new Ville($_POST["ville"]);
$hotel = new Hebergement();
$option = new Option();


if(!empty($_POST["latitude"]) && !empty($_POST["longitude"]) && empty($_POST["link"])){
    try{
        $info = $hotel->getHotelbyName($_POST["libelle"]);
        $nom_doss = bin2hex(random_bytes(32));
        if(empty($ville->getUuid()) && file_exists($nom_doss) == false){
            mkdir("../src/".$nom_doss, 0700);
            for($i=0; $i < count($_FILES["file"]) - 1; $i++){
                $newName = $_POST["libelle"].$i;
                $target_dir = "../src/".$nom_doss."/";
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
                $target_file = $target_dir . $newName . "." . "png";
                $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
                move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
            }
        }else{
            for($i=0; $i < count($_FILES["file"]["name"]); $i++){
                $newName = $_POST["libelle"].$i;
                $target_dir = "../src/".$ville->getUuid()."/";
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
                $target_file = $target_dir . $newName . "." . "png";
                $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
                move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
            }
        }


        $hotel->addHotel($_POST["libelle"], $_POST["description"], $_POST["ville"], $_POST["latitude"], $_POST["longitude"], $_POST["prix"]);
        $option->addOptions($info["idHebergement"], $_POST["options"]);
    
        header("location:../admin/addHotel.php");

    }catch(exception $e){
        // header("location:../admin/index.php");
    }
    
}elseif(empty($_POST["latitude"]) && empty($_POST["longitude"]) && !empty($_POST["link"])){
    try{

        $latitude = substr($_POST["link"], strpos($_POST["link"], "3d") + 2);
        $latitude = substr($latitude, 0, 5 - strlen($latitude));

        $longitude = substr($_POST["link"], strpos($_POST["link"], "4d") + 2);
        $longitude = substr($longitude, 0, 4 - strlen($latitude) - 3);

        $hotel->addHotel($_POST["name"], $_POST["description"], $_POST["ville"], $latitude, $longitude, $_POST["prix"], $_POST["television"], $_POST["lave_linge"], $_POST["seche_linge"], $_POST["cuisine"], $_POST["réfrigirateur"], $_POST["four"], $_POST["parking"], $_POST["linge_de_maison"], $_POST["vaisselle"], $_POST["cafetiere"], $_POST["climatisation"]);

        header("location:../admin/addHotel.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}