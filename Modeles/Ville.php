<?php

class Ville extends Modele {

    private $idVille;
    private $libelle;
    private $latitude;
    private $longitude;
    private $idRegion;
    private $description;

    public function __construct($idVille = null){

        if ( $idVille != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM villes WHERE idVille = ?");
            $requete->execute([$idVille]);
            $infoVille =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idVille = $infoVille["idVille"];
            $this->libelle = $infoVille["libelle"];
            $this->latitude = $infoVille["latitude"];
            $this->longitude = $infoVille["longitude"];
            $this->longitude = $infoVille["idRegion"];
            $this->description = $infoVille["description"];

        }
        
    }

    public function initialiserVille($idVille, $libelle, $latitude, $longitude, $idRegion, $description){

        $this->idVille = $idVille;
        $this->adresse = $adresse;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->longitude = $idRegion;
        $this->description = $description;

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

    public function getRegion($idVille){
        $requete = $this->getBdd()->prepare("SELECT regions.* FROM regions inner join villes using(idRegion) where idVille = ?");
        $requete->execute([$idVille]);
        $infoRegion_Ville = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoRegion_Ville;
    }

    public function addVille($libelle, $latitude, $longitude, $region, $description){
        $requete = $this->getBdd()->prepare("INSERT into villes(libelle, latitude, longitude, idRegion, description) values(?,?,?,?,?)");
        $requete->execute([$libelle, $latitude, $longitude, $region, $description]);
    }

}