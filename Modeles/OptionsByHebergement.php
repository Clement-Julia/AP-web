<?php

class OptionsByHebergement extends Modele {

    private $idOption;
    private $idHebergement;

    public function __construct($idOption = null, $idHebergement = null){

        if ( $idOption != null && $idHebergement != null){

            $requete = $this->getBdd()->prepare("SELECT * FROM options_by_hebergement WHERE idOption = ? AND idHebergement = ?");
            $requete->execute([$idOption, $idHebergement]);
            $infosForHebergement =  $requete->fetchAll(PDO::FETCH_ASSOC);

            $this->idOption = $infosForHebergement["idOption"];
            $this->idHebergement = $infosForHebergement["idHebergement"];

        }
        
    }

    public function initialiserOption($idOption, $idHebergement){
        $this->idOption = $idOption;
        $this->idHebergement = $idHebergement;
    }

    public function getIdOption(){
        return $this->idOption;
    }

    public function getIdHebergement(){
        return $this->idHebergement;
    }

}