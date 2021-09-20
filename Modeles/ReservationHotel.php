<?php

class ReservationHotel extends Modele {

    private $idReservationHotel;
    private $dateDebut;
    private $dateFin;
    private $prix;
    private $codeReservation;
    private $nbJours;
    private $idVoyage;

    public function __construct($idReservationHotel = null)
    {
        if ($idReservationHotel != null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hotels WHERE idReservationHotel = ?");
            $requete->execute([$idReservationHotel]);
            $infoReservation =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idReservationHotel = $infoReservation["idReservationHotel"];
            $this->dateDebut = $infoReservation["dateDebut"];
            $this->dateFin = $infoReservation["dateFin"];
            $this->prix = $infoReservation["prix"];
            $this->codeReservation = $infoReservation["codeReservation"];
            $this->nbJours = $infoReservation["nbJours"];
            $this->idVoyage = $infoReservation["idVoyage"];
        }
    }

    public function initializeReservationHotel($idReservationHotel, $dateDebut, $dateFin, $prix, $codeReservation, $nbJours, $idVoyage)
    {
        $this->idReservationHotel = $idReservationHotel;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->prix = $prix;
        $this->codeReservation = $codeReservation;
        $this->nbJours = $nbJours;
        $this->idVoyage = $idVoyage;
    }

    public function getIdReservationHotel()
    {
        return $this->idReservationHotel;
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
            $dates[] = $tableau[0] . "-" . $tableau[1] . "-" . $tableau[2];
            $tableau[2] += 1;
        }
        $dates[] = $idHebergement;

        $str = "SELECT * FROM reservations_Hotels WHERE (". str_repeat("(dateDebut <= ? && dateFin >= ?) OR ", $nb + 1);
        $str = substr($str, 0, -3);
        $str .= ") AND idHebergement = ?;";

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