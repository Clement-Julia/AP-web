<?php

class Ville extends Modele {

    private $idVille;
    private $libelle;
    private $latitude;
    private $longitude;
    private $idRegion;
    private $description;
    private $hebergements = []; //tableau d'objet hebergement

    public function __construct($idVille = null){

        if ( $idVille != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM villes WHERE idVille = ?");
            $requete->execute([$idVille]);
            $infoVille =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idVille = $infoVille["idVille"];
            $this->libelle = $infoVille["libelle"];
            $this->latitude = $infoVille["latitude"];
            $this->longitude = $infoVille["longitude"];
            $this->description = $infoVille["description"];
            $this->idRegion = $infoVille["idRegion"];

            $requete = $this->getBdd()->prepare("SELECT * FROM hebergement WHERE idVille = ?");
            $requete->execute([$idVille]);
            $infosHebergement = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($infosHebergement as $item){

                $hebergement = new Hebergement();
                $hebergement->initialiserHebergement($item["idHebergement"], $item["libelle"], $item["description"], $item["idVille"], $item["latitude"], $item["longitude"], $item["prix"]);
                $this->hebergements[] = $hebergement;

            }

        }
        
    }

    public function initialiserVille($idVille, $libelle, $latitude, $longitude, $idRegion, $description){

        $this->idVille = $idVille;
        $this->libelle = $libelle;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->longitude = $idRegion;
        $this->description = $description;

        // voir si ici aussi on requete pour les hebergements avec initialisation de ceux ci ?

    }

    public function getIdVille(){
        return $this->idVille;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function getLatitude(){
        return $this->latitude;
    }

    public function getLongitude(){
        return $this->longitude;
    }

    public function getIdRegion(){
        return $this->idRegion;
    }

    public function getDescription(){
        return $this->description;
    }
    
    public function getHebergements(){
        return $this->hebergements;
    }

    public function getRegion($idVille){
        $requete = $this->getBdd()->prepare("SELECT regions.* FROM regions inner join villes using(idRegion) where idVille = ?");
        $requete->execute([$idVille]);
        $infoRegion_Ville = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoRegion_Ville;
    }

    public function addVille($libelle, $latitude, $longitude, $region, $description){
        $requete = $this->getBdd()->prepare("INSERT into villes(libelle, latitude, longitude, idRegion, description) values(?,?,?,?,?)");
        $requete->execute([$libelle, $latitude, $longitude, $region, $description]);
    }

}