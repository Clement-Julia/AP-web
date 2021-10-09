<?php

class Favoris extends Modele {

    private $idHebergement;
    private $idUtilisateur;

    public function __construct($idHebergement = null, $idUtilisateur = null){

        if ( $idHebergement != null &&  $idUtilisateur != null){

            $requete = $this->getBdd()->prepare("SELECT * FROM favoris WHERE idHebergement = ? AND idUtilisateur = ?");
            $requete->execute([$idHebergement, $idUtilisateur]);
            $infoFavoris =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idHebergement = $infoFavoris["idHebergement"];
            $this->idUtilisateur = $infoFavoris["idUtilisateur"];

        }
        
    }

    public function initialiserFavoris($idHebergement, $idUtilisateur){

        $this->idHebergement = $idHebergement;
        $this->idUtilisateur = $idUtilisateur;

    }

    public function getIdHebergement(){
        return $this->idHebergement;
    }

    public function getIdUtilisateur(){
        return $this->idUtilisateur;
    }

    public function getAllFavorisForUser($idUtilisateur){
        $requete = $this->getBdd()->prepare("SELECT * FROM favoris WHERE idUtilisateur = ?");
        $requete->execute([$idUtilisateur, $idUtilisateur]);
        return  $requete->fetchAll(PDO::FETCH_ASSOC);
    }

}