<?php
require_once "traitement.php";
$hotel = new Hebergement();

if(!empty($_POST["latitude"]) && !empty($_POST["longitude"])){
    try{

        if(!empty($_FILES["file"])){
            for($i=0; $i < count($_FILES["file"]["name"]); $i++){
                $newName = $_POST["libelle"].$i;
                $target_dir = "../src/uuid/".$_GET["uuid"]."/";
                $imageFileType = strtolower(pathinfo($_FILES["file"]["name"][$i],PATHINFO_EXTENSION));
                $target_file = $target_dir . $newName . "." . "png";
                $check = getimagesize($_FILES["file"]["tmp_name"][$i]);
                move_uploaded_file($_FILES["file"]["tmp_name"][$i], $target_file);
            }
        }

        $hotel->updateHotel($_POST["options"], $_GET["id"]);

        header("location:../admin/modifHotel.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}