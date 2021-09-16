<?php

class Agence extends Modele {

    private $idAgence;
    private $adresse;
    private $code_postal;
    private $idVille;
    private $idRegion;

    public function __construct($idAgence = null){

        if ( $idAgence != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM agences WHERE idAgence = ?");
            $requete->execute([$idAgence]);
            $infoAgence =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idAgence = $infoAgence["idAgence"];
            $this->adresse = $infoAgence["adresse"];
            $this->code_postal = $infoAgence["code_postal"];
            $this->idVille = $infoAgence["idVille"];
            $this->idRegion = $infoAgence["idRegion"];

        }
        
    }

    public function initialiserAgence($idAgence, $adresse, $code_postal, $idVille, $idRegion){

        $this->idAgence = $idAgence;
        $this->adresse = $adresse;
        $this->code_postal = $code_postal;
        $this->idVille = $idVille;
        $this->idRegion = $idRegion;

    }

    public function getIdAgence(){
        return $this->idAgence;
    }

    public function getAdresse(){
        return $this->adresse;
    }

    public function getCode_postal(){
        return $this->code_postal;
    }

    public function getIdVille(){
        return $this->idVille;
    }

    public function getIdRegion(){
        return $this->idRegion;
    }

    public function getRegion($idAgence){
        $requete = $this->getBdd()->prepare("SELECT regions.* FROM regions inner join agences using(idAgence) where idAgence = ?");
        $requete->execute([$idAgence]);
        $infoRegion_Agence = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoRegion_Agence;
    }

    public function getVille($idAgence){
        $requete = $this->getBdd()->prepare("SELECT villes.* FROM villes inner join agences using(idVille) where idAgence = ?");
        $requete->execute([$idAgence]);
        $infoAgence_Ville = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoAgence_Ville;
    }

}