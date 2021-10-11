<?php
require_once "traitement.php";
$ville = new Ville();

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

        $ville->updateVille($_POST["libelle"], $_POST["latitude"], $_POST["longitude"], $_POST["region"], $_POST["description"], $_GET["id"]);
        header("location:../admin/modifVille.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}