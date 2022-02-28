<?php

class ReservationHebergement extends Modele {

    private $idReservationHebergement;
    private $dateDebut;
    private $dateFin;
    private $prix;
    private $codeReservation;
    private $nbJours;
    private $idVoyage;
    private $idUtilisateur;
    private $idHebergement;

    public function __construct($idReservationHebergement = null)
    {
        if ($idReservationHebergement != null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hebergement WHERE idReservationHebergement = ?");
            $requete->execute([$idReservationHebergement]);
            $infoReservation =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idReservationHebergement = $infoReservation["idReservationHebergement"];
            $this->dateDebut = $infoReservation["dateDebut"];
            $this->dateFin = $infoReservation["dateFin"];
            $this->prix = $infoReservation["prix"];
            $this->codeReservation = $infoReservation["code_reservation"];
            $this->nbJours = $infoReservation["nbJours"];
            $this->idVoyage = $infoReservation["idVoyage"];
            $this->idUtilisateur = $infoReservation["idUtilisateur"];
            $this->idHebergement = $infoReservation["idHebergement"];
        }
    }

    public function initializeReservationHebergement($idReservationHebergement, $dateDebut, $dateFin, $prix, $codeReservation, $nbJours, $idVoyage, $idUtilisateur, $idHebergement)
    {
        $this->idReservationHebergement = $idReservationHebergement;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->prix = $prix;
        $this->codeReservation = $codeReservation;
        $this->nbJours = $nbJours;
        $this->idVoyage = $idVoyage;
        $this->idUtilisateur = $idUtilisateur;
        $this->idHebergement = $idHebergement;
    }

    public function getIdReservationHebergement()
    {
        return $this->idReservationHebergement;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }
    
    public function getDateFin()
    {
        return $this->dateFin;
    }
    
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }
    
    public function getPrix()
    {
        return $this->prix;
    }

    public function getCodeReservation()
    {
        return $this->codeReservation;
    }
    
    public function getNbJours()
    {
        return $this->nbJours;
    }

    public function getIdVoyage()
    {
        return $this->idVoyage;
    }

    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

    public function getIdHebergement()
    {
        return $this->idHebergement;
    }

    public function insertReservationHebergement(int $idUtilisateur, int $idVoyage, $prix, $dateDebut, $dateFin, int $nbJours, int $idHebergement){

        $code = "qqch";
        while($code != null){
            $codeReservation = "HEB" . rand(10000, 99999);
            $requete = $this->getBdd()->prepare("SELECT code_reservation FROM reservations_hebergement WHERE code_reservation = ?");
            $requete->execute([$codeReservation]);
            $code = $requete->fetch(PDO::FETCH_ASSOC);
        }

        $requete = $this->getBdd()->prepare("INSERT INTO reservations_hebergement (idUtilisateur, idVoyage, code_reservation, prix, dateDebut, dateFin, nbJours, idHebergement) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $requete->execute([$idUtilisateur, $idVoyage, $codeReservation, $prix, $dateDebut, $dateFin, $nbJours, $idHebergement]);

    }

    public function isItBookedForThisDate($date, int $nb, int $idHebergement){

        $dates = [];

        for ($i = 0; $i <= $nb; $i++){

            $Date = new DateTime($date . "+" . $i . " days");
            $dates[] = $Date->format('Y-m-d');
        }
        
        $str = "SELECT * FROM reservations_hebergement WHERE 1 ";
        
        foreach($dates as $date){
            $str .= " AND ? BETWEEN dateDebut AND dateFin ";
        }
        
        $dates[] = $idHebergement;
        $str .= " AND idHebergement = ?;";

        $requete = $this->getBdd()->prepare($str);
        $requete->execute($dates);
        $isBooked = $requete->rowCount();

        if ($isBooked == 0){
            return $dates[count($dates) - 2];
        } else {
            return false;
        }

    }

    public function getHebergementById($hebergementId){
        $requete = $this->getBdd()->prepare("SELECT villes.idRegion, villes.libelle as villeNom, hebergement.libelle as nomHebergement, hebergement.description FROM hebergement INNER JOIN reservations_hebergement USING(idHebergement) INNER JOIN villes USING(idVille) INNER JOIN regions USING(idRegion) WHERE idHebergement = ?");
        $requete->execute([$hebergementId]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    // ELLE EST RIDICULE CETTE FONCTION ???? XD A REVOIR 
    public function getReservationHebergementById($reservationHebergementId){
        $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hebergement WHERE idReservationHebergement = ?");
        $requete->execute([$reservationHebergementId]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteReservationHebergement($reservationHebergementId){
        $requete = $this->getBdd()->prepare("call sup_reservations_hebergement(?)");
        $requete->execute([$reservationHebergementId]);
    }

    public function getIdVilleByHebergementId($idHebergement){
        $requete = $this->getBdd()->prepare("SELECT idVille FROM reservations_hebergement INNER JOIN hebergement USING(idHebergement) WHERE idHebergement = ?");
        $requete->execute([$idHebergement]);
        return $requete->fetch(PDO::FETCH_ASSOC)['idVille'];
    }

    public function getIdRegionByHebergementId($idHebergement){
        $requete = $this->getBdd()->prepare("SELECT idRegion FROM reservations_hebergement INNER JOIN hebergement USING(idHebergement) INNER JOIN villes USING(idVille) WHERE idHebergement = ?");
        $requete->execute([$idHebergement]);
        return $requete->fetch(PDO::FETCH_ASSOC)['idRegion'];
    }

    public function getFreeHebergement($idVille, $dateDebut, $nbJours){

        try {
            $date = new DateTime($dateDebut);
        } catch (\Throwable $th) {
            return false;
        }

        $requete = $this->getBdd()->prepare("SELECT * FROM `hebergement` LEFT JOIN reservations_hebergement USING(idHebergement) WHERE idVille = ?");
        $requete->execute([$idVille]);
        $hebergements = $requete->fetchAll(PDO::FETCH_ASSOC);

        $array = [];

        foreach($hebergements as $hebergement){
            if(!$hebergement['idReservationHebergement'] == null){
                $date = new DateTime($dateDebut);
                $Reservation = new ReservationHebergement();
                $isBooked = $Reservation->isItBookedForThisDate($date->format('Y-m-d'), $nbJours, $hebergement['idHebergement']);

                if (!empty($isBooked)){
                    $array[] = new Hebergement($hebergement['idHebergement']);
                }

            } else {
                $array[] = new Hebergement($hebergement['idHebergement']);
            }

        }

        return $array;

    }

    public function updateReservationHebergement($codeReservation, $prix, $dateDebut, $dateFin, $nbJours, $idVoyage, $idUtilisateur, $idHebergement, $reservationHebergementId){

        $requete = $this->getBdd()->prepare("UPDATE reservations_hebergement SET code_reservation = ?, prix = ?, dateDebut = ?, dateFin = ?, nbjours = ?, idVoyage = ?, idUtilisateur = ?, idHebergement = ? WHERE idReservationHebergement = ?");
        $requete->execute([$codeReservation, $prix, $dateDebut, $dateFin, $nbJours, $idVoyage, $idUtilisateur, $idHebergement, $reservationHebergementId]);
    }

}