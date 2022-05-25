<?php
require_once "traitement.php";

$demande = $_SESSION["demande". $_GET["id"]];

$admin = new Admin();
$hotel = new Hebergement();
$ville = new Ville($demande["idVille"]);

if($_GET["command"] == "acc"){

    try{

        if(!$ville->getUuid()){
            //Création du dossier uuid ville
            $nom_doss_ville = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss_ville) != false){
                $nom_doss_ville = bin2hex(random_bytes(32));
            }
            mkdir("../assets/src/uuid/".$nom_doss_ville, 0777);

            //Création du dossier uuid hébergement
            $nom_doss_hotel = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss_hotel) != false){
                $nom_doss_hotel = bin2hex(random_bytes(32));
            }

            mkdir("../assets/src/uuid/".$nom_doss, 0777);
            $ville->updateVille($ville->getLibelle(), $ville->getLatitude(), $ville->getLongitude(), $ville->getCode_postal(), $ville->getIdRegion(), $ville->getDescription(), $nom_doss_ville, $ville->getIdVille());
            $admin->acceptHebergementEnAttente($demande["idHebergement"], $nom_doss_hotel);
        }else{

            //Création du dossier uuid hébergement
            $nom_doss_hotel = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss_hotel) != false){
                $nom_doss_hotel = bin2hex(random_bytes(32));
            }
            mkdir("../assets/src/uuid/".$nom_doss_hotel, 0777);

            $admin->acceptHebergementEnAttente($demande["idHebergement"], $nom_doss_hotel);
        }
        header("location:../admin/validHotel.php?success=acc");

    }catch(exception $e){
        header("location:../admin/validHotel.php?error");
    }

}elseif($_GET["command"] == "ref"){
    try{

        $admin->supHebergementEnAttente($demande["idHebergement"]);
        header("location:../admin/validHotel.php?success=ref");

    }catch(exception $e){
        header("location:../admin/validHotel.php?error");
    }
}else{
    header("location:../admin/validHotel.php?error");
}