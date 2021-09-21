<?php

class Region extends Modele {

    private $idRegion;
    private $libelle;
    private $latitude;
    private $longitude;
    private $lvZoom;
    private $villes = [];

    public function __construct(int $idRegion = null){

        if ( $idRegion != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM regions WHERE idRegion = ?");
            $requete->execute([$idRegion]);
            $infoRegion =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idRegion = $infoRegion["idRegion"];
            $this->libelle = $infoRegion["libelle"];
            $this->latitude = $infoRegion["latitude"];
            $this->longitude = $infoRegion["longitude"];
            $this->lvZoom = $infoRegion["lv_zoom"];

            $requete = $this->getBdd()->prepare("SELECT * FROM villes WHERE idRegion = ?");
            $requete->execute([$idRegion]);
            $infosVilles = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($infosVilles as $item){

                $ville = new Ville();
                $ville->initialiserVille($item["idVille"], $item["libelle"], $item["latitude"], $item["longitude"], $item["idRegion"], $item["description"]);
                $this->villes[] = $ville;

            }

        }
        
    }

    public function initialiserRegion($idRegion, $libelle){

        $this->idRegion = $idRegion;
        $this->libelle = $libelle;

    }

    public function getIdRegion(){
        return $this->idRegion;
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
    public function getLvZoom(){
        return $this->lvZoom;
    }
    public function getVilles(){
        return $this->villes;
    }


    // il va falloir remplacer cette partie par une initialisation automatique des objets ville dans le tableau villes

    public function getAllregion(){
        $requete = $this->getBdd()->prepare("SELECT * FROM regions");
        $requete->execute();
        $Allregion = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $Allregion;
    }

    public function getTownByRegionId($idRegion){

        $requete = $this->getBdd()->prepare("SELECT idVille, villes.libelle, villes.latitude, villes.longitude FROM regions INNER JOIN villes USING(idRegion) WHERE idRegion = ?");
        $requete->execute([$idRegion]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAllRegions(){
        $requete = $this->getBdd()->prepare("SELECT * FROM regions");
        $requete->execute();
        $regions = $requete->fetchAll(PDO::FETCH_ASSOC);

        $array = [];

        foreach ($regions as $region){
            $array[$region['libelle']] = $region['idRegion'];
        }

        return $array;
    }

    public function countRegion(){
        $requete = $this->getBdd()->prepare("SELECT count(idRegion) as nbr from regions");
        $requete->execute();
        $info_nbr = $requete->fetch(PDO::FETCH_ASSOC);
        return $info_nbr;
    }

}