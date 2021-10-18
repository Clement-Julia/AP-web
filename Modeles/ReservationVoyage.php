<?php

class ReservationVoyage extends Modele {

    private $idReservationVoyage;
    private $prix;
    private $codeReservation;
    private $idUtilisateur;
    private $isBuilding;
    private $reservationsHebergement = [];

    public function __construct($idReservationVoyage = null)
    {
        if ($idReservationVoyage != null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM reservations_voyages WHERE idReservationVoyage = ?");
            $requete->execute([$idReservationVoyage]);
            $infoReservation =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idReservationVoyage = $infoReservation["idReservationVoyage"];
            $this->prix = $infoReservation["prix"];
            $this->codeReservation = $infoReservation["code_reservation"];
            $this->idUtilisateur = $infoReservation["idUtilisateur"];
            $this->isBuilding = $infoReservation["is_building"];

            $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hebergement WHERE idVoyage = ? ORDER BY idReservationHebergement ASC;");
            $requete->execute([$idReservationVoyage]);
            $infosIdReservationsHotels = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ($infosIdReservationsHotels as $item)
            {

                $Reservation = new ReservationHebergement();
                $Reservation->initializeReservationHebergement($item['idReservationHebergement'], $item['dateDebut'], $item['dateFin'], $item['prix'], $item['code_reservation'], $item['nbJours'], $item['idVoyage'], $item['idUtilisateur'], $item['idHebergement'] );
                $this->reservationsHebergement[] = $Reservation;

            }
        }
    }

    public function initializeReservationVoyage($idReservationVoyage, $prix, $codeReservation, $idUtilisateur, $isBuilding)
    {
        $this->idReservationVoyage = $idReservationVoyage;
        $this->prix = $prix;
        $this->codeReservation = $codeReservation;
        $this->idUtilisateur = $idUtilisateur;
        $this->isBuilding = $isBuilding;

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

    public function getReservationHebergement()
    {
        return $this->reservationsHebergement;
    }

    public function getIsBuilding()
    {
        return $this->isBuilding;
    }

    public function insertBaseTravel($prix, $codeReservation, $idUtilisateur, $boolean){

        $requete = $this->getBdd()->prepare("INSERT INTO reservations_voyages (prix, code_reservation, idUtilisateur, is_building) VALUE (?, ?, ?, ?)");
        $requete->execute([$prix, $codeReservation, $idUtilisateur, $boolean]);

        $requete = $this->getBdd()->prepare("SELECT idReservationVoyage FROM reservations_voyages WHERE idUtilisateur = ? AND prix = ? AND code_reservation = ? AND is_building = ?");
        $requete->execute([$idUtilisateur, $prix, $codeReservation, $boolean]);
        return $requete->fetch(PDO::FETCH_ASSOC)['idReservationVoyage'];

    }

    public function getIdRegionForBuildingTravelByUserId($idUtilisateur){

        $requete = $this->getBdd()->prepare("SELECT villes.idRegion FROM reservations_voyages
        INNER JOIN reservations_hebergement ON idReservationVoyage = idVoyage
        INNER JOIN hebergement USING(idHebergement)
        INNER JOIN villes USING(idVille)
        WHERE reservations_voyages.idUtilisateur = ? AND is_building = ?;");
        $requete->execute([$idUtilisateur, 1]);
        return $requete->fetch(PDO::FETCH_ASSOC)['idRegion'];
    }

    public function getIsBuildingByUserId($idUtilisateur){
        $requete = $this->getBdd()->prepare("SELECT * FROM reservations_voyages WHERE idUtilisateur = ? AND is_building = ?");
        $requete->execute([$idUtilisateur, true]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function updateTravelPrice($price, $idReservationVoyage){
        $requete = $this->getBdd()->prepare("UPDATE reservations_voyages SET prix = ? WHERE idReservationVoyage = ?");
        $requete->execute([$price, $idReservationVoyage]);
    }

    public function getVilleLatLngByUserId($idUtilisateur){

        $requete = $this->getBdd()->prepare("SELECT villes.latitude, villes.longitude FROM reservations_voyages
        INNER JOIN reservations_hebergement ON idReservationVoyage = idVoyage
        INNER JOIN hebergement USING(idHebergement)
        INNER JOIN villes USING(idVille)
        WHERE reservations_voyages.idUtilisateur = ?
        AND is_building = ? ORDER BY dateDebut");
        $requete->execute([$idUtilisateur, true]);
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLastReservationHebergement($idReservationVoyage){
        $requete = $this->getBdd()->prepare("SELECT MAX(idReservationHebergement) as idReservationHebergement FROM reservations_hebergement WHERE idVoyage = ?");
        $requete->execute([$idReservationVoyage]);
        $lastID = $requete->fetch(PDO::FETCH_ASSOC)['idReservationHebergement'];

        $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hebergement WHERE idReservationHebergement = ?");
        $requete->execute([$lastID]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function getIdBuildingTravelByUserId($idUtilisateur){
        $requete = $this->getBdd()->prepare("SELECT * FROM reservations_voyages WHERE idUtilisateur = ? AND is_building = ?");
        $requete->execute([$idUtilisateur, true]);
        return $requete->fetch(PDO::FETCH_ASSOC)['idReservationVoyage'];
    }

    public function deleteBuildingTravelByUserId($idUtilisateur){
        $requete = $this->getBdd()->prepare("DELETE reservations_voyages, reservations_hebergement FROM reservations_voyages INNER JOIN reservations_hebergement ON reservations_voyages.idReservationVoyage = reservations_hebergement.idVoyage WHERE (reservations_voyages.idUtilisateur = ? OR reservations_hebergement.idUtilisateur = ?) AND is_building = ?");
        $requete->execute([$idUtilisateur, $idUtilisateur, true]);
    }

    public function deleteBuildingWithIdReservationVoyage($idReservationVoyage){
        $requete = $this->getBdd()->prepare("DELETE FROM reservations_voyages WHERE idReservationVoyage = ? AND is_building = ?");
        $requete->execute([$idReservationVoyage, true]);
    }

    public function updatePrix($idVoyage){
        $requete = $this->getBdd()->prepare("SELECT SUM(prix) as prix FROM `reservations_hebergement` WHERE idVoyage = ?");
        $requete->execute([$idVoyage]);
        $prixTotal = $requete->fetch(PDO::FETCH_ASSOC)['prix'];
        
        $requete = $this->getBdd()->prepare("UPDATE reservations_voyages SET prix = ? WHERE idReservationVoyage = ?");
        $requete->execute([$prixTotal, $idVoyage]);
    }

    // Permet lors du paiement d'un voyage en construction, de le valider.
    public function updateIsBuilding(int $idReservationVoyage, bool $boolean){
        if($boolean){ $boolean = 1; } else { $boolean = 0;}
        $requete = $this->getBdd()->prepare("UPDATE reservations_voyages SET is_building = ? WHERE idReservationVoyage = ?");
        $requete->execute([$boolean, $idReservationVoyage]);
    }

}