<?php

class Ville extends Modele {

    private $idVille;
    private $libelle;
    private $latitude;
    private $longitude;
    private $code_postal;
    private $idRegion;
    private $description;
    private $uuid;
    private $hebergements = []; // tableau d'objet hebergement
    private $activites = []; // tableau d'objet d'activite

    public function __construct($idVille = null){

        if ( $idVille != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM villes WHERE idVille = ?");
            $requete->execute([$idVille]);
            $infoVille =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idVille = $infoVille["idVille"];
            $this->libelle = $infoVille["libelle"];
            $this->latitude = $infoVille["latitude"];
            $this->longitude = $infoVille["longitude"];
            $this->code_postal = $infoVille["code_postal"];
            $this->description = $infoVille["description"];
            $this->idRegion = $infoVille["idRegion"];
            $this->uuid = $infoVille["uuid"];

            $requete = $this->getBdd()->prepare("SELECT * FROM hebergement WHERE idVille = ?");
            $requete->execute([$idVille]);
            $infosHebergement = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($infosHebergement as $item){

                $hebergement = new Hebergement();
                $hebergement->initialiserHebergement($item["idHebergement"], $item["libelle"], $item["description"], $item["idVille"], $item["latitude"], $item["longitude"], $item["adresse"], $item["prix"], $item["uuid"]);
                $this->hebergements[] = $hebergement;

            }

            $requete = $this->getBdd()->prepare("SELECT * FROM activites_by_ville INNER JOIN activites USING(idActivite) WHERE idVille = ?");
            $requete->execute([$idVille]);
            $infosActivites = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($infosActivites as $item){

                $Activite = new Activite();
                $Activite->initialiserActiviteByVille($item["idActivite"], $item["libelle"], $item["icon"], $item["latitude"], $item["longitude"], $item["description"]);
                $this->activites[] = $Activite;
                
            }

        }
        
    }

    public function initialiserVille($idVille, $libelle, $latitude, $longitude, $code_postal, $idRegion, $description, $uuid){

        $this->idVille = $idVille;
        $this->libelle = $libelle;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->code_postal = $code_postal;
        $this->description = $description;
        $this->idRegion = $idRegion;
        $this->description = $description;
        $this->uuid = $uuid;

        // voir si ici aussi on requete pour les hebergements avec initialisation de ceux ci ?

    }

    public function getIdVille(){
        return $this->idVille;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function setLibelle($libelle){
        $this->libelle = $libelle;
    }

    public function getLatitude(){
        return $this->latitude;
    }

    public function setLatitude($latitude){
        $this->latitude = $latitude;
    }

    public function getLongitude(){
        return $this->longitude;
    }

    public function setLongitude($longitude){
        $this->longitude = $longitude;
    }

    public function getCode_postal(){
        return $this->code_postal;
    }

    public function setCode_postal($code_postal){
        $this->code_postal = $code_postal;
    }

    public function getIdRegion(){
        return $this->idRegion;
    }

    public function setIdRegion($idRegion){
        $this->idRegion = $idRegion;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }
    
    public function getHebergements(){
        return $this->hebergements;
    }

    public function getUuid(){
        return $this->uuid;
    }

    public function setUuid($uuid){
        $this->uuid = $uuid;
    }

    public function getRegion($idVille){
        $requete = $this->getBdd()->prepare("SELECT regions.* FROM regions inner join villes using(idRegion) where idVille = ?");
        $requete->execute([$idVille]);
        $infoRegion_Ville = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoRegion_Ville;
    }

    public function addVille($libelle, $latitude, $longitude, $code_postal, $region, $description, $uuid){
        try {

            $requete = $this->getBdd()->prepare("INSERT into villes(libelle, latitude, longitude, code_postal, idRegion, description, uuid) values(?,?,?,?,?,?,?)");
            $requete->execute([$libelle, $latitude, $longitude, $code_postal, $region, $description, $uuid]);

        } catch (Exception $e){
            return false;
        }

        return true;
        
    }

    public function countVille(){
        $requete = $this->getBdd()->prepare("SELECT count(idVille) as nbr from villes");
        $requete->execute();
        $info_nbr = $requete->fetch(PDO::FETCH_ASSOC);
        return $info_nbr;
    }

    public function getAllville(){
        $requete = $this->getBdd()->prepare("SELECT * FROM villes");
        $requete->execute();
        $Allville = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $Allville;
    }

    public function getVillebyName($libelle){
        $requete = $this->getBdd()->prepare("SELECT * FROM villes WHERE libelle = ?");
        $requete->execute([$libelle]);
        $infoVille = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoVille;
    }

    public function updateVille($libelle, $latitude, $longitude, $code_postal, $idRegion, $description, $uuid, $id){
        try {
            $requete = $this->getBdd()->prepare("UPDATE villes set libelle = ?, latitude = ?, longitude = ?, code_postal = ?, idRegion = ?, description = ?, uuid = ? where idVille = ?");
            $requete->execute([$libelle, $latitude, $longitude, $code_postal, $idRegion, $description, $uuid, $id]);
        } catch (Exception $e){
            return false;
        }
        return true;
    }

    public function supVille($id, $uuid = null){
        try {
            $requete = $this->getBdd()->prepare("call delete_ville_by_id(?)");
            $requete->execute([$id]);

            // Si uuid est pas null (si on est pas en test unitaire (la raison est en lien avec le chemin relatif des fonctions))
            if($uuid != null){
                $folder = scandir("../assets/src/tuuid/".$uuid);
                for($i = 2; $i < count($folder); $i++){
                    unlink("../assets/src/uuid/".$uuid."/".$folder[$i]);
                }
                rmdir("../assets/src/uuid/".$uuid);
            }
            
        } catch (Exception $e){
            return false;
        }
        return true;
    }
    
    public function getFreeHebergement($date, $idVille){

        // $requete = $this->getBdd()->prepare("SELECT * FROM hebergement LEFT JOIN villes using(idVille) LEFT JOIN reservations_hebergement USING(idHebergement) where idVille = ?");
        $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hebergement INNER JOIN hebergement USING(idHebergement) where idVille = ? AND reservations_hebergement.dateDebut >= ?");
        $requete->execute([$idVille, $date->format('Y-m-d')]);
        $hebergements = $requete->fetchAll(PDO::FETCH_ASSOC);

        $response = [];

        foreach($hebergements as $hebergement){

                $Hebergement = new Hebergement($hebergement['idHebergement']);
                $array = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement(), $date->format('Y-m-d'));

                if(!in_array($date->format('Y-m-d'), $array) && !empty($array)){
                    $lenght = count($array);
                    for ($i = 0; $i < $lenght; $i++){
                        if($array[$i] < $date->format('Y-m-d')){
                            unset($array[$i]);
                        }
                    }

                    if(count($array) == 0){
                        $response[$Hebergement->getIdHebergement()][0] = "Disponible plus de 14 nuits";
                        $response[$Hebergement->getIdHebergement()][2] = 999;
                    } else {
                        $origin = new DateTime($date->format('Y-m-d'));
                        $target = new DateTime($array[key($array)]);
                        $nbJours = $origin->diff($target)->format('%a');

                        if($nbJours > 15){
                            $response[$Hebergement->getIdHebergement()][0] = "Disponible plus de 14 nuits";
                            $response[$Hebergement->getIdHebergement()][2] = $nbJours;
                        } else {
                            $response[$Hebergement->getIdHebergement()][0] = "Disponible " . $nbJours . ($nbJours > 1 ? " nuits" : " nuit");
                            $response[$Hebergement->getIdHebergement()][2] = $nbJours;
                        }
                    }

                } else if(count($array) == 0){
                    $response[$Hebergement->getIdHebergement()][0] = "Disponible plus de 14 nuits";
                    $response[$Hebergement->getIdHebergement()][2] = 999;
                } else {
                    $response[$Hebergement->getIdHebergement()][0] = "Indisponible";
                    $response[$Hebergement->getIdHebergement()][2] = 0;
                }
                $response[$Hebergement->getIdHebergement()][1] = $Hebergement;
                
            
        }
        return $response;
    }

    public function getVillebyUuid($uuid){
        $requete = $this->getBdd()->prepare("SELECT * FROM villes WHERE uuid = ?");
        $requete->execute([$uuid]);
        $infoVille = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoVille;
    }

}