<?php

class Hebergement extends Modele {

    private $idHebergement;
    private $libelle;
    private $description;
    private $idVille;
    private $latitude;
    private $longitude;
    private $prix;
    private $options = [];

    public function __construct($idHebergement = null){

        if ( $idHebergement != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM hebergement WHERE idHebergement = ?");
            $requete->execute([$idHebergement]);
            $infoHotel =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idHebergement = $infoHotel["idHebergement"];
            $this->libelle = $infoHotel["libelle"];
            $this->description = $infoHotel["description"];
            $this->idVille = $infoHotel["idVille"];
            $this->latitude = $infoHotel["latitude"];
            $this->longitude = $infoHotel["longitude"];
            $this->prix = $infoHotel["prix"];
            
            $requete = $this->getBdd()->prepare("SELECT * FROM options_by_hebergement INNER JOIN options USING(idOption) WHERE idHebergement = ? AND actif = ?");
            $requete->execute([$idHebergement, 1]);
            $infosOptions =  $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($infosOptions as $infoOption){
                $opt = new Option();
                $opt->initialiserOption($infoOption['idOption'], $infoOption['libelle'], $infoOption['icon']);
                $this->options[] = $opt;
            }

        }
        
    }

    public function initialiserHebergement($idHebergement, $libelle, $description, $idVille, $latitude, $longitude, $prix){

        $this->idHebergement = $idHebergement;
        $this->libelle = $libelle;
        $this->description = $description;
        $this->idVille = $idVille;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->prix = $prix;

    }

    public function getIdHebergement(){
        return $this->idHebergement;
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

    public function getLatitude(){
        return $this->latitude;
    }

    public function getLongitude(){
        return $this->longitude;
    }
    
    public function getPrix(){
        return $this->prix;
    }

    public function getOptions(){
        return $this->options;
    }

    public function getIdRegionByIdHebergement(int $idHebergement){
        $requete = $this->getBdd()->prepare("SELECT idRegion FROM hebergement INNER JOIN villes USING(idVille) INNER JOIN regions USING(idRegion) WHERE idHebergement = ?");
        $requete->execute([$idHebergement]);
        return $requete->fetch(PDO::FETCH_ASSOC)['idRegion'];
    }

}