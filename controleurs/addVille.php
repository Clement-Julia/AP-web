<?php
require_once "traitement.php";
$ville = new Ville();

if(!empty($_POST["latitude"]) && !empty($_POST["longitude"]) && empty($_POST["link"])){
    try{
        $ville->addVille($_POST["libelle"], $_POST["latitude"], $_POST["longitude"], $_POST["region"], $_POST["description"]);
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

        $ville->addVille($_POST["libelle"], $latitude, $longitude, $_POST["region"], $_POST["description"]);
        header("location:../admin/addVille.php");
    }catch(exception $e){
        header("location:../admin/index.php");
    }
}else{
    header("location:../vues/index.php");
}