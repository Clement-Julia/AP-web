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

            $this->idReservationHotel = $infoReservation["idReservationHebergement"];
            $this->dateDebut = $infoReservation["dateDebut"];
            $this->dateFin = $infoReservation["dateFin"];
            $this->prix = $infoReservation["prix"];
            $this->codeReservation = $infoReservation["codeReservation"];
            $this->nbJours = $infoReservation["nbJours"];
            $this->idVoyage = $infoReservation["idVoyage"];
            $this->idUtilisateur = $infoReservation["idUtilisateur"];
            $this->idHebergement = $infoReservation["idHebergement"];
        }
    }

    public function initializeReservationHotel($idReservationHebergement, $dateDebut, $dateFin, $prix, $codeReservation, $nbJours, $idVoyage, $idUtilisateur, $idHebergement)
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

    public function getDateFin()
    {
        return $this->dateFin;
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

    public function isItBookedForThisDate($date, int $nb, int $idHebergement){

        $tableau = explode("-", $date);
        $lastDayOfMonth = date('t', mktime(0, 0, 0, $tableau[1], 1, $tableau[0]));
        $dates = [];

        for ($i = 0; $i <= $nb; $i++){

            if($tableau[2] > $lastDayOfMonth){
                $tableau[2] = 1;
                $tableau[1] += 1;
                if($tableau[1] > 12){
                    $tableau[1] = 1;
                    $tableau[0] += 1;
                }
            }
            $dates[] = $tableau[0] . "-" . $tableau[1] . "-" . $tableau[2];
            $tableau[2] += 1;
        }
        
        $str = "SELECT * FROM reservations_Hebergement WHERE 1 ";
        
        foreach($dates as $date){
            $str .= " AND ? BETWEEN dateDebut AND dateFin ";
        }
        
        $dates[] = $idHebergement;
        $str .= " AND idHebergement = ?;";

        // echo "<pre>";
        // print_r($dates);
        // echo "</pre>";
        // echo "<br>";
        // echo $str;
        // exit;

        $requete = $this->getBdd()->prepare($str);
        $requete->execute($dates);
        $isBooked = $requete->rowCount();

        if ($isBooked == 0){
            return $dates[count($dates) - 2];
        } else {
            return false;
        }

    }

}