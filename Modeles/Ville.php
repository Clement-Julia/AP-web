<?php

class Ville extends Modele {

    private $idVille;
    private $libelle;
    private $latitude;
    private $longitude;
    private $idRegion;
    private $description;
    private $uuid;
    private $hebergements = []; //tableau d'objet hebergement

    public function __construct($idVille = null){

        if ( $idVille != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM villes WHERE idVille = ?");
            $requete->execute([$idVille]);
            $infoVille =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idVille = $infoVille["idVille"];
            $this->libelle = $infoVille["libelle"];
            $this->latitude = $infoVille["latitude"];
            $this->longitude = $infoVille["longitude"];
            $this->description = $infoVille["description"];
            $this->idRegion = $infoVille["idRegion"];
            $this->uuid = $infoVille["uuid"];

            $requete = $this->getBdd()->prepare("SELECT * FROM hebergement WHERE idVille = ?");
            $requete->execute([$idVille]);
            $infosHebergement = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($infosHebergement as $item){

                $hebergement = new Hebergement();
                $hebergement->initialiserHebergement($item["idHebergement"], $item["libelle"], $item["description"], $item["idVille"], $item["latitude"], $item["longitude"], $item["prix"]);
                $this->hebergements[] = $hebergement;

            }

        }
        
    }

    public function initialiserVille($idVille, $libelle, $latitude, $longitude, $idRegion, $description){

        $this->idVille = $idVille;
        $this->libelle = $libelle;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
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

    public function getLatitude(){
        return $this->latitude;
    }

    public function getLongitude(){
        return $this->longitude;
    }

    public function getIdRegion(){
        return $this->idRegion;
    }

    public function getDescription(){
        return $this->description;
    }
    
    public function getHebergements(){
        return $this->hebergements;
    }

    public function getUuid(){
        return $this->uuid;
    }

    public function getRegion($idVille){
        $requete = $this->getBdd()->prepare("SELECT regions.* FROM regions inner join villes using(idRegion) where idVille = ?");
        $requete->execute([$idVille]);
        $infoRegion_Ville = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoRegion_Ville;
    }

    public function addVille($libelle, $latitude, $longitude, $region, $description, $uuid){
        $requete = $this->getBdd()->prepare("INSERT into villes(libelle, latitude, longitude, idRegion, description, uuid) values(?,?,?,?,?,?)");
        $requete->execute([$libelle, $latitude, $longitude, $region, $description, $uuid]);
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

    public function updateVille($libelle, $latitude, $longitude, $idRegion, $description, $id){
        $requete = $this->getBdd()->prepare("UPDATE villes set libelle = ?, latitude = ?, longitude = ?, idRegion = ?, description = ? where idVille = ?");
        $requete->execute([$libelle, $latitude, $longitude, $idRegion, $description, $id]);
    }

    public function supVille($libelle){
        $requete = $this->getBdd()->prepare("DELETE from villes where libelle = ?");
        $requete->execute([$libelle]);
    }

}