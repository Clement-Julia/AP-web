<?php

class Option extends Modele {

    private $idOption;
    private $libelle;
    private $icon;

    public function __construct($idOption = null){

        if ( $idOption != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM options WHERE idOption = ?");
            $requete->execute([$idOption]);
            $infoOption =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idOption = $infoOption["idOption"];
            $this->libelle = $infoOption["libelle"];
            $this->icon = $infoOption["icon"];

        }
        
    }

    public function initialiserOption($idOption, $libelle, $icon){
        $this->idOption = $idOption;
        $this->libelle = $libelle;
        $this->icon = $icon;
    }

    public function getIdOption(){
        return $this->idOption;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function getIcon(){
        return $this->icon;
    }

}