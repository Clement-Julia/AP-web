<?php
require_once "traitement.php";
$hotel = new Hebergement();

$tab = ["television", "lave_linge", "seche_linge", "cuisine", "réfrigirateur", "four", "parking", "linge_de_maison", "vaisselle", "cafetiere", "climatisation"];

for($i = 0; $i < count($tab); $i++){
    if(empty($_POST[$tab[$i]])){
        $_POST[$tab[$i]] = 0;
    }else{
        $_POST[$tab[$i]] = 1;
    }
}

if(!empty($_POST["latitude"]) && !empty($_POST["longitude"]) && empty($_POST["link"])){
    try{

        $hotel->addHotel($_POST["name"], $_POST["description"], $_POST["ville"], $_POST["latitude"], $_POST["longitude"], $_POST["prix"], $_POST["television"], $_POST["lave_linge"], $_POST["seche_linge"], $_POST["cuisine"], $_POST["réfrigirateur"], $_POST["four"], $_POST["parking"], $_POST["linge_de_maison"], $_POST["vaisselle"], $_POST["cafetiere"], $_POST["climatisation"]);

        header("location:../admin/addHotel.php");
    }catch(exception $e){
        header("location:../admin/index.php");
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