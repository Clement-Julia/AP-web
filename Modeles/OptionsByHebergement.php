<?php

class OptionsByHebergement extends Modele {

    private $idOption;
    private $idHebergement;
    private $actif; // boolean

    public function __construct($idOption = null, $idHebergement = null){

        if ( $idOption != null && $idHebergement != null){

            $requete = $this->getBdd()->prepare("SELECT * FROM options_by_hebergement WHERE idOption = ? AND idHebergement = ?");
            $requete->execute([$idOption, $idHebergement]);
            $infosForHebergement =  $requete->fetchAll(PDO::FETCH_ASSOC);

            $this->idOption = $infosForHebergement["idOption"];
            $this->idHebergement = $infosForHebergement["idHebergement"];
            $this->actif = $infosForHebergement["actif"];

        }
        
    }

    public function initialiserOption($idOption, $idHebergement, $actif){
        $this->idOption = $idOption;
        $this->idHebergement = $idHebergement;
        $this->actif = $actif;
    }

    public function getIdOption(){
        return $this->idOption;
    }

    public function getIdHebergement(){
        return $this->idHebergement;
    }

    public function getActif(){
        return $this->actif;
    }

}