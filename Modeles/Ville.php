<?php

class Ville extends Modele {

    private $idVille; // int
    private $libelle; // string
    private $latitude; // float
    private $longitude; // float
    private $idRegion; // int
    private $description; // string
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
        $this->idRegion = $idRegion;
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

    public function getHebergements(){
        return $this->hebergements;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getRegion($idVille){
        $requete = $this->getBdd()->prepare("SELECT regions.* FROM regions inner join villes using(idRegion) where idVille = ?");
        $requete->execute([$idVille]);
        $infoRegion_Ville = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoRegion_Ville;
    }

    public function getFreeHebergement($date, $idVille){

        $requete = $this->getBdd()->prepare("SELECT * FROM hebergement LEFT JOIN villes using(idVille) LEFT JOIN reservations_hebergement USING(idHebergement) where idVille = ?");
        $requete->execute([$idVille]);
        $hebergements = $requete->fetchAll(PDO::FETCH_ASSOC);

        $response = [];

        foreach($hebergements as $hebergement){

                $Hebergement = new Hebergement($hebergement['idHebergement']);
                $array = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement(), $date->format('Y-m-d'));

                if(!in_array($date->format('Y-m-d'), $array) && !empty($array)){
                    $lenght = count($array);
                    for ($i = 0; $i < $lenght; $i++){
                        if($array[$i] < $date->format('Y-m-d')){
                            unset($array[$i]);
                        }
                    }

                    if(count($array) == 0){
                        $response[$Hebergement->getIdHebergement()][0] = "disponible plus de 14 jours";
                    } else {
                        $origin = new DateTime($date->format('Y-m-d'));
                        $target = new DateTime($array[key($array)]);
                        $nbJours = $origin->diff($target)->d - 1;

                        if($nbJours > 14){
                            $response[$Hebergement->getIdHebergement()][0] = "disponible plus de 14 jours";
                            $response[$Hebergement->getIdHebergement()][2] = $nbJours;
                        } else {
                            $response[$Hebergement->getIdHebergement()][0] = "disponible " . $nbJours . " jours";
                            $response[$Hebergement->getIdHebergement()][2] = $nbJours;
                        }
                    }

                } else if(count($array) == 0){
                    $response[$Hebergement->getIdHebergement()][0] = "disponible plus de 14 jours";
                    $response[$Hebergement->getIdHebergement()][2] = 999;
                } else {
                    $response[$Hebergement->getIdHebergement()][0] = "indisponible";
                    $response[$Hebergement->getIdHebergement()][2] = 0;
                }
                $response[$Hebergement->getIdHebergement()][1] = $Hebergement;
                
            
        }

        return $response;
    }

}