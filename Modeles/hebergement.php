<?php

class Hebergement extends Modele {

    private $idHotel;
    private $libelle;
    private $description;
    private $idVille;
    private $lattitude;
    private $longitude;
    private $prix;

    public function __construct($idHotel = null){

        if ( $idHotel != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM hebergements WHERE idHotel = ?");
            $requete->execute([$idHotel]);
            $infoHotel =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idHotel = $infoHotel["idHotel"];
            $this->libelle = $infoHotel["libelle"];
            $this->description = $infoHotel["description"];
            $this->idVille = $infoHotel["idVille"];
            $this->lattitude = $infoHotel["lattitude"];
            $this->longitude = $infoHotel["longitude"];
            $this->prix = $infoHotel["prix"];

        }
        
    }

    public function initialiserHebergement($idHotel, $libelle, $description, $idVille, $lattitude, $longitude, $prix){

        $this->idHotel = $idHotel;
        $this->libelle = $libelle;
        $this->description = $description;
        $this->idVille = $idVille;
        $this->lattitude = $lattitude;
        $this->longitude = $longitude;
        $this->prix = $prix;

    }

    public function getIdHotel(){
        return $this->idHotel;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getIdVille(){
        return $this->idVille;
    }

    public function getLattitude(){
        return $this->lattitude;
    }

    public function getLongitude(){
        return $this->lattitude;
    }
    
    public function getPrix(){
        return $this->prix;
    }

    public function getAvis($idHotel){
        $requete = $this->getBdd()->prepare("SELECT avis.* FROM avis inner join hebergements using(idHotel) where idHotel = ?");
        $requete->execute([$idHotel]);
        $infoAvis_Hotel = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoAvis_Hotel;
    }
}