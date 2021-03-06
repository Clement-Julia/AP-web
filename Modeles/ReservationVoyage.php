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

    public function insertBaseTravel($prix, $idUtilisateur, $boolean){

        while(empty($codeReservationVOY)){
            $codeReservationVOY = "VOY" . rand(100000000, 999999999);
            $requete = $this->getBddPublic()->prepare("SELECT code_reservation FROM reservations_voyages WHERE code_reservation = ?");
            $requete->execute([$codeReservationVOY]);
            $return = $requete->fetch(PDO::FETCH_ASSOC)['code_reservation'];
            if(!empty($return)){
                $codeReservationVOY = $return;
            }
        }

        $requete = $this->getBdd()->prepare("INSERT INTO reservations_voyages (prix, code_reservation, idUtilisateur, is_building) VALUE (?, ?, ?, ?)");
        $requete->execute([$prix, $codeReservationVOY, $idUtilisateur, $boolean]);

        $requete = $this->getBdd()->prepare("SELECT idReservationVoyage FROM reservations_voyages WHERE idUtilisateur = ? AND prix = ? AND code_reservation = ? AND is_building = ?");
        $requete->execute([$idUtilisateur, $prix, $codeReservationVOY, $boolean]);
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
        try {
            $requete = $this->getBdd()->prepare("UPDATE reservations_voyages SET prix = ? WHERE idReservationVoyage = ?");
            $requete->execute([$price, $idReservationVoyage]);
        } catch (Exception $e){
            return false;
        }
        return true;
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
        try {
            $requete = $this->getBdd()->prepare("DELETE reservations_voyages, reservations_hebergement FROM reservations_voyages INNER JOIN reservations_hebergement ON reservations_voyages.idReservationVoyage = reservations_hebergement.idVoyage WHERE (reservations_voyages.idUtilisateur = ? OR reservations_hebergement.idUtilisateur = ?) AND is_building = ?");
            $requete->execute([$idUtilisateur, $idUtilisateur, true]);
        } catch (Exception $e){
            return false;
        }
        return true;
    }

    public function deleteBuildingTravelByTravelId($idReservationVoyage){
        try {
            $requete = $this->getBdd()->prepare("DELETE reservations_voyages, reservations_hebergement FROM reservations_voyages INNER JOIN reservations_hebergement ON reservations_voyages.idReservationVoyage = reservations_hebergement.idVoyage WHERE reservations_voyages.idReservationVoyage = ? OR reservations_hebergement.idVoyage = ?");
            $requete->execute([$idReservationVoyage, $idReservationVoyage]);
        } catch (Exception $e){
            return false;
        }
        return true;
    }

    public function deleteBuildingWithIdReservationVoyage($idReservationVoyage){
        $requete = $this->getBdd()->prepare("call sup_reservation_voyage(?,?)");
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
    
    public function getVoyageByUser($idUtilisateur){
        $requete = $this->getBdd()->prepare("SELECT reservations_voyages.idReservationVoyage, villes.libelle as ville, hebergement.libelle as hebergement, hebergement.description as description, reservations_hebergement.code_reservation as code, is_building, reservations_hebergement.prix, dateDebut, dateFin, nbjours FROM `reservations_voyages` inner join reservations_hebergement on reservations_voyages.idReservationVoyage = reservations_hebergement.idVoyage INNER join hebergement USING(idHebergement) INNER join villes USING(idVille) where reservations_hebergement.idUtilisateur = ?");
        $requete->execute([$idUtilisateur]);
        $result = $requete->fetchALL(PDO::FETCH_ASSOC);

        $tab = [];
        if (count($result) > 0){
            $id = $result[0]["idReservationVoyage"];
            $i = 0;
            $x = 0;
            foreach($result as $test){

                if($test["idReservationVoyage"] != $id){
                    $id = $test["idReservationVoyage"];
                    $i++;
                }

                $tab[$i]["id"] = $id;
                $tab[$i]["voyage"][$x]["ville"] = $test["ville"];
                $tab[$i]["voyage"][$x]["hebergement"] = $test["hebergement"];
                $tab[$i]["voyage"][$x]["description"] = $test["description"];
                $tab[$i]["voyage"][$x]["code"] = $test["code"];
                $tab[$i]["voyage"][$x]["is_building"] = $test["is_building"];
                $tab[$i]["voyage"][$x]["prix"] = $test["prix"];
                $tab[$i]["voyage"][$x]["dateDebut"] = $test["dateDebut"];
                $tab[$i]["voyage"][$x]["dateFin"] = $test["dateFin"];
                $tab[$i]["voyage"][$x]["nbjours"] = $test["nbjours"];

                $x++;
                
            }
        }

        return $tab;
    }

    public function getVoyageById($idReservationVoyage){
        $requete = $this->getBdd()->prepare("SELECT reservations_voyages.idReservationVoyage, villes.libelle as ville, hebergement.libelle as hebergement, hebergement.description as description, reservations_hebergement.code_reservation as code, is_building, reservations_hebergement.prix, dateDebut, dateFin, nbjours FROM `reservations_voyages` inner join reservations_hebergement on reservations_voyages.idReservationVoyage = reservations_hebergement.idVoyage INNER join hebergement USING(idHebergement) INNER join villes USING(idVille) where reservations_voyages.idReservationVoyage = ?");
        $requete->execute([$idReservationVoyage]);
        $result = $requete->fetchALL(PDO::FETCH_ASSOC);
        
        return $result;
    }

    public function getDateVoyage($idReservationVoyage){
        $requete = $this->getBdd()->prepare("SELECT min(dateDebut) as dateDebut, max(dateFin) as dateFin FROM `reservations_voyages` rv RIGHT JOIN reservations_hebergement rh ON rv.idReservationVoyage = rh.idVoyage WHERE rv.idReservationVoyage = ?");
        $requete->execute([$idReservationVoyage]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function getCountVoyageByUser($idUtilisateur){
        $requete = $this->getBdd()->prepare("SELECT count(DISTINCT idReservationVoyage) FROM `reservations_voyages` INNER JOIN `reservations_hebergement` on reservations_voyages.idReservationVoyage = reservations_hebergement.idVoyage WHERE reservations_voyages.idUtilisateur = ?");
        $requete->execute([$idUtilisateur]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllReservationVoyage(){
        $requete = $this->getBdd()->prepare("SELECT rv.idReservationVoyage, rv.prix, u.nom, u.prenom, r.libelle FROM `reservations_voyages` rv
        INNER JOIN reservations_hebergement rh on rv.idReservationVoyage = rh.idVoyage
        INNER JOIN hebergement h USING(idHebergement)
        INNER JOIN utilisateurs u ON rv.idUtilisateur = u.idUtilisateur
        INNER JOIN villes v USING(idVille)
        INNER JOIN regions r USING(idRegion)
        WHERE rv.is_building = 0
        GROUP BY rv.idReservationVoyage
        ");
        $requete->execute();
        return $requete->fetchAll(PDO::FETCH_ASSOC);
    }

}