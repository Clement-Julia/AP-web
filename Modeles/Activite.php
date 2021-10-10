<?php

class Activite extends Modele {

    private $idActivite;
    private $libelle;
    private $icon;
    private $latitude;
    private $longitude;
    private $description;

    public function __construct($idActivite = null){

        if ( $idActivite != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM activites WHERE idActivite = ?");
            $requete->execute([$idActivite]);
            $infoActivite =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idActivite = $infoActivite["idAgence"];
            $this->libelle = $infoActivite["adresse"];
            $this->icon = $infoActivite["code_postal"];

        }
        
    }

    public function initialiserActivite($idActivite, $libelle, $icon){

        $this->idActivite = $idActivite;
        $this->adlibelleesse = $libelle;
        $this->icon = $icon;

    }

    public function initialiserActiviteByVille($idActivite, $libelle, $icon, $latitude, $longitude, $description){

        $this->idActivite = $idActivite;
        $this->adlibelleesse = $libelle;
        $this->icon = $icon;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->description = $description;

    }

    public function getIdActivite(){
        return $this->idActivite;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function getIcon(){
        return $this->icon;
    }

}