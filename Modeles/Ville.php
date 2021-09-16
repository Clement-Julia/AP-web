<?php

class Ville extends Modele {

    private $idVille;
    private $libelle;
    private $latitude;
    private $longitude;
    private $idRegion;

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

        }
        
    }

    public function initialiserVille($idVille, $libelle, $latitude, $longitude, $idRegion){

        $this->idVille = $idVille;
        $this->adresse = $adresse;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->longitude = $idRegion;

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

    public function getRegion($idVille){
        $requete = $this->getBdd()->prepare("SELECT regions.* FROM regions inner join villes using(idRegion) where idVille = ?");
        $requete->execute([$idVille]);
        $infoRegion_Ville = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoRegion_Ville;
    }

}