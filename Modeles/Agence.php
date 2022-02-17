<?php

class Agence extends Modele {

    private $idAgence;
    private $adresse;
    private $idVille;

    public function __construct($idAgence = null){

        if ( $idAgence != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM agences WHERE idAgence = ?");
            $requete->execute([$idAgence]);
            $infoAgence =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idAgence = $infoAgence["idAgence"];
            $this->adresse = $infoAgence["adresse"];
            $this->idVille = $infoAgence["idVille"];

        }
        
    }

    public function initialiserAgence($idAgence, $adresse, $code_postal, $idVille, $idRegion){

        $this->idAgence = $idAgence;
        $this->adresse = $adresse;
        $this->idVille = $idVille;
        $this->idRegion = $idRegion;

    }

    public function getIdAgence(){
        return $this->idAgence;
    }

    public function getAdresse(){
        return $this->adresse;
    }

    public function getIdVille(){
        return $this->idVille;
    }
    
    public function getVille($idAgence){
        $requete = $this->getBdd()->prepare("SELECT villes.* FROM villes inner join agences using(idVille) where idAgence = ?");
        $requete->execute([$idAgence]);
        $infoAgence_Ville = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoAgence_Ville;
    }

}