<?php

class Region extends Modele {

    private $idRegion;
    private $libelle;

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

}