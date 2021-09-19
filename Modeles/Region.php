<?php

class Region extends Modele {

    private $idRegion;
    private $libelle;
    private $latitude;
    private $longitude;
    private $lvZoom;
    private $villes = [];

    public function __construct($idRegion = null){

        if ( $idRegion != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM regions WHERE idRegion = ?");
            $requete->execute([$idRegion]);
            $infoRegion =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idRegion = $infoRegion["idRegion"];
            $this->libelle = $infoRegion["libelle"];
            $this->latitude = $infoRegion["latitude"];
            $this->longitude = $infoRegion["longitude"];
            $this->lvZoom = $infoRegion["lv_zoom"];

        }
        
    }

    public function initialiserRegion($idRegion, $libelle){

        $this->idRegion = $idRegion;
        $this->libelle = $libelle;

    }

    public function getIdRegion(){
        return $this->idRegion;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function getAllregion(){
        $requete = $this->getBdd()->prepare("SELECT * FROM regions");
        $requete->execute();
        $Allregion = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $Allregion;
    }

    public function getTownByRegionId($idRegion){

        $requete = $this->getBdd()->prepare("SELECT idVille, villes.libelle, villes.latitude, villes.longitude FROM regions INNER JOIN villes USING(idRegion) WHERE idRegion = ?");
        $requete->execute([$idRegion]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);

    }

    public function countRegion(){
        $requete = $this->getBdd()->prepare("SELECT count(idRegion) as nbr from regions");
        $requete->execute();
        $info_nbr = $requete->fetch(PDO::FETCH_ASSOC);
        return $info_nbr;
    }

}