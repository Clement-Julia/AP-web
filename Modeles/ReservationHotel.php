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
}