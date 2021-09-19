<?php

class Region extends Modele {

    private $idRegion;
    private $libelle;
    private $hebergements = [];

    public function __construct($idRegion = null){

        if ( $idRegion != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM regions WHERE idRegion = ?");
            $requete->execute([$idRegion]);
            $infoRegion =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idRegion = $infoRegion["idRegion"];
            $this->libelle = $infoRegion["libelle"];

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

    public function getLodgingByRegion($idRegion){

        $requete = $this->getBdd()->prepare("SELECT hebergement.libelle, hebergement.latitude, hebergement.longitude, prix FROM regions INNER JOIN villes USING(idRegion) INNER JOIN hebergement USING(idVille) WHERE idRegion = ?");
        $requete->execute([$idRegion]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);

    }

}