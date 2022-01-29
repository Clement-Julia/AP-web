<?php
require_once "traitement.php";
$admin = new Admin();
$hotel = new Hebergement();
$ville = new Ville();

$demande = $_SESSION["demande". $_GET["id"]];


if($_GET["command"] == "acc"){

    try{

        if($demande["idVille"] != null){
            //Création du dossier
            $nom_doss = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss) != false){
                $nom_doss = bin2hex(random_bytes(32));
            }
            mkdir("../assets/src/uuid/".$nom_doss, 0700);

            $hotel->addHotel($demande["libelle"], $demande["description"], $demande["idVille"], $demande["latitude"], $demande["longitude"], $demande["adresse"], $demande["prix"], $nom_doss, $demande["idUtilisateur"]);
            $admin->supHebergementEnAttente($demande["id_admin_valid_hebergement"]);
        }else{
            //Création du dossier uuid ville
            $nom_doss_ville = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss_ville) != false){
                $nom_doss_ville = bin2hex(random_bytes(32));
            }
            mkdir("../assets/src/uuid/".$nom_doss_ville, 0700);

            //Création du dossier uuid hébergement
            $nom_doss_hotel = bin2hex(random_bytes(32));
            while(file_exists("../src/uuid/".$nom_doss_hotel) != false){
                $nom_doss_hotel = bin2hex(random_bytes(32));
            }
            mkdir("../assets/src/uuid/".$nom_doss_hotel, 0700);

            $ville->addVille($demande["nomVille"], $demande["latitudeVille"], $demande["longitudeVille"], $demande["code_postal"], $demande["idRegion"], null, $nom_doss_ville);
            $nameVille = $ville->getVillebyName($demande["nomVille"]);
            $hotel->addHotel($demande["libelle"], $demande["description"], $nameVille["idVille"], $demande["latitude"], $demande["longitude"], $demande["adresse"], $demande["prix"], $nom_doss_hotel, $demande["idUtilisateur"]);
            $admin->supHebergementEnAttente($demande["id_admin_valid_hebergement"]);
        }
        header("location:../admin/validHotel.php?success=acc");

    }catch(exception $e){
        header("location:../admin/validHotel.php?error");
    }

}elseif($_GET["command"] == "ref"){
    try{

        $admin->refDemande($demande["id_admin_valid_hebergement"]);
        header("location:../admin/validHotel.php?success=ref");

    }catch(exception $e){
        header("location:../admin/validHotel.php?error");
    }
}elseif($_GET["command"] == "sup"){
    try{

        $admin->supHebergementEnAttente($demande["id_admin_valid_hebergement"]);
        header("location:../admin/validHotel.php?success=sup");

    }catch(exception $e){
        header("location:../admin/validHotel.php?error");
    }
}