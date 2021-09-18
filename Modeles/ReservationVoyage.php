<?php

class ReservationVoyage extends Modele {

    private $idReservationVoyage;
    private $prix;
    private $codeReservation;
    private $idUtilisateur;
    private $reservationsHotels = [];

    public function __construct($idReservationVoyage = null)
    {
        if ($idReservationVoyage != null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hotels WHERE idReservation = ?");
            $requete->execute([$idReservationVoyage]);
            $infoReservation =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idReservationVoyage = $infoReservation["idReservationVoyage"];
            $this->prix = $infoReservation["prix"];
            $this->codeReservation = $infoReservation["codeReservation"];
            $this->idUtilisateur = $infoReservation["idUtilisateur"];

            $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hotels WHERE idVoyage = ?;");
            $requete->execute([$idReservationVoyage]);
            $infosIdReservationsHotels = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($infosIdReservationsHotels as $item)
            {
                $this->reservationsHotels[] = new ReservationHotel($item->idReservationHotel);
            }
        }
    }

    public function initializeReservationVoyage($idReservationVoyage, $prix, $codeReservation, $idUtilisateur)
    {
        $this->idReservationVoyage = $idReservationVoyage;
        $this->prix = $prix;
        $this->codeReservation = $codeReservation;
        $this->idUtilisateur = $idUtilisateur;

    }

    public function getIdReservationVoyage()
    {
        return $this->idReservationVoyage;
    }

    public function getPrix()
    {
        return $this->prix;
    }

    public function getCodeReservation()
    {
        return $this->codeReservation;
    }

    public function getIdUtilisateur()
    {
        return $this->idUtilisateur;
    }

}