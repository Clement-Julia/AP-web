<?php

class Hebergement extends Modele {

    private $idHebergement;
    private $libelle;
    private $description;
    private $idVille;
    private $latitude;
    private $longitude;
    private $adresse;
    private $prix;
    private $uuid;
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
            $this->adresse = $infoHotel["adresse"];
            $this->prix = $infoHotel["prix"];
            $this->uuid = $infoHotel["uuid"];
            
            $requete = $this->getBdd()->prepare("SELECT * FROM options_by_hebergement INNER JOIN options USING(idOption) WHERE idHebergement = ?");
            $requete->execute([$idHebergement]);
            $infosOptions =  $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($infosOptions as $infoOption){
                $opt = new Option();
                $opt->initialiserOption($infoOption['idOption'], $infoOption['libelle'], $infoOption['icon']);
                $this->options[] = $opt;
            }

        }
        
    }

    public function initialiserHebergement($idHebergement, $libelle, $description, $idVille, $latitude, $longitude, $adresse, $prix, $uuid){

        $this->idHebergement = $idHebergement;
        $this->libelle = $libelle;
        $this->description = $description;
        $this->idVille = $idVille;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->adresse = $adresse;
        $this->prix = $prix;
        $this->uuid = $uuid;

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

    public function getAdresse(){
        return $this->adresse;
    }
    
    public function getPrix(){
        return $this->prix;
    }

    public function getUuid(){
        return $this->uuid;
    }

    public function setUuid($uuid){
        $this->uuid = $uuid;
    }

    public function getOptions(){
        return $this->options;
    }

    public function countHotel(){
        $requete = $this->getBdd()->prepare("SELECT count(idHebergement) as nbr from hebergement");
        $requete->execute();
        $info_nbr = $requete->fetch(PDO::FETCH_ASSOC);
        return $info_nbr;
    }

    public function getAllHotel(){
        $requete = $this->getBdd()->prepare("SELECT * FROM hebergement");
        $requete->execute();
        $Allhotel = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $Allhotel;
    }

    public function getHotelbyName($libelle){
        $requete = $this->getBdd()->prepare("SELECT * FROM hebergement WHERE libelle = ?");
        $requete->execute([$libelle]);
        $infoHotel = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoHotel;
    }

    public function addHotel($libelle, $description, $idVille, $latitude, $longitude, $adresse, $prix, $uuid, $idUtilisateur){

        $requete = $this->getBdd()->prepare("INSERT into hebergement(libelle, description, idVille, latitude, longitude, adresse, prix, uuid, idUtilisateur, dateEnregistrement, actif) values(?,?,?,?,?,?,?,?,?, NOW(),?)");

        $requete->execute([$libelle, $description, $idVille, $latitude, $longitude, $adresse, $prix, $uuid, $idUtilisateur, 1]);
    }

    public function updateHotel($libelle, $description, $idVille, $latitude, $longitude, $adresse, $prix, $uuid, $id){

        $requete = $this->getBdd()->prepare("UPDATE hebergement set libelle = ?, hebergement.description = ?, idVille = ?, latitude = ?, longitude = ?, adresse = ?, prix = ?, uuid = ? where idHebergement = ?");

        $requete->execute([$libelle, $description, $idVille, $latitude, $longitude, $adresse, $prix, $uuid, $id]);
    }

    public function supHotel($idHebergement, $uuid){
        if($uuid != null){
            $folder = scandir("../assets/src/uuid/".$uuid);
            for($i = 2; $i < count($folder); $i++){
                unlink("../assets/src/uuid/".$uuid."/".$folder[$i]);
            }
            rmdir("../assets/src/uuid/".$uuid);
        }
        

        $requete = $this->getBdd()->prepare("call sup_hebergement(?)");
        $requete->execute([$idHebergement]);
    }
    
    public function getIdRegionByIdHebergement(int $idHebergement){
        $requete = $this->getBdd()->prepare("SELECT idRegion FROM hebergement INNER JOIN villes USING(idVille) INNER JOIN regions USING(idRegion) WHERE idHebergement = ?");
        $requete->execute([$idHebergement]);
        return $requete->fetch(PDO::FETCH_ASSOC)['idRegion'];
    }

    public function getWhenHebergementIsBooking(int $idHebergement, $date = null){

        if ($date == null){
            $requete = $this->getBdd()->prepare("SELECT dateDebut, nbJours FROM reservations_hebergement INNER JOIN reservations_voyages ON idVoyage = idReservationVoyage WHERE idHebergement = ? AND is_building = ?");
            $requete->execute([$idHebergement, false]);
        } else {
            $requete = $this->getBdd()->prepare("SELECT dateDebut, nbJours FROM reservations_hebergement INNER JOIN reservations_voyages ON idVoyage = idReservationVoyage WHERE idHebergement = ? AND (? BETWEEN dateDebut AND dateFIn OR dateDebut > ?) AND is_building = ?");
            $requete->execute([$idHebergement, $date, $date, false]);
        }
        
        $array = [];
        foreach ($requete->fetchAll(PDO::FETCH_ASSOC) as $reservation){
            
            $begin = new DateTime( $reservation['dateDebut'] );
            $end = new DateTime( $reservation['dateDebut'] );
            $end = $end->modify( '+' . $reservation['nbJours'] . ' day' );

            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval ,$end);
            
            foreach($daterange as $date){
                $array[] = $date->format("Y-m-d");
            }
        }
        
        usort($array, "sortFunction");

        return $array;
    }

    public function getVilleLibelle($idVille){
        $requete = $this->getBdd()->prepare("SELECT villes.libelle FROM hebergement INNER JOIN villes USING(idVille) WHERE idVille = ?");
        $requete->execute([$idVille]);
        return $requete->fetch(PDO::FETCH_ASSOC)['libelle'];
    }

}