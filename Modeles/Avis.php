<?php

class Avis extends Modele {

    private $idAvis;
    private $date;
    private $note;
    private $commentaire;
    private $idUtilisateur;
    private $idHebergement;

    public function __construct($idAvis = null){

        if ( $idAvis != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM avis WHERE idAvis = ?");
            $requete->execute([$idAvis]);
            $infoAvis =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idAvis = $infoAvis["idAvis"];
            $this->date = $infoAvis["date"];
            $this->note = $infoAvis["note"];
            $this->commentaire = $infoAvis["commentaire"];
            $this->idUtilisateur = $infoAvis["idUtilisateur"];
            $this->idHebergement = $infoAvis["idHebergement"];

        }
        
    }

    public function initialiserAvis($idAvis, $date, $note, $commentaire, $idUtilisateur, $idHebergement){

        $this->idAvis = $idAvis;
        $this->date = $date;
        $this->note = $note;
        $this->commentaire = $commentaire;
        $this->idUtilisateur = $idUtilisateur;
        $this->idHebergement = $idHebergement;

    }

    public function getIdAvis(){
        return $this->idAvis;
    }

    public function getDate(){
        return $this->date;
    }

    public function getNote(){
        return $this->note;
    }

    public function getCommentaire(){
        return $this->commentaire;
    }

    public function getIdUtilisateur(){
        return $this->idUtilisateur;
    }

    public function getidHebergement(){
        return $this->idHebergement;
    }

    
    public function getAllAvis(){
        $requete = $this->getBdd()->prepare("SELECT * FROM hebergement inner join avis using(idHebergement) where avis.idUtilisateur = ?");
        $requete->execute([$_SESSION['idUtilisateur']]);
        $infoAvis =  $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infoAvis;
    }

    public function getAllAvisByHebergement($libelle){
        $requete = $this->getBdd()->prepare("SELECT * FROM hebergement inner join avis using(idHebergement) inner join utilisateurs on utilisateurs.idUtilisateur = avis.idUtilisateur where hebergement.libelle = ?");
        $requete->execute([$libelle]);
        $infoAvis =  $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infoAvis;
    }

    public function getHebergementbynonAvis($idUtilisateur){
        $requete = $this->getBdd()->prepare("SELECT h.*, rh.dateFin FROM hebergement h INNER JOIN reservations_hebergement rh using(idHebergement) LEFT JOIN avis ON rh.dateFin = avis.date WHERE rh.idUtilisateur = ? AND avis.date IS NULL AND rh.dateFin < NOW()");
        $requete->execute([$idUtilisateur]);
        $infoAvis =  $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infoAvis;
    }

    public function addAvis($note, $commentaire, $idUtilisateur, $idHebergement, $dateFinReservationHebergement){
        $requete = $this->getBdd()->prepare("INSERT into avis(date, note, commentaire, idUtilisateur, idHebergement) values(?,?,?,?,?)");
        $requete->execute([$dateFinReservationHebergement, $note, $commentaire, $idUtilisateur, $idHebergement]);
    }

    public function updateAvis($note, $commentaire, $idAvis){
        $requete = $this->getBdd()->prepare("UPDATE avis set note = ?, commentaire = ? where idAvis = ?");
        $requete->execute([$note, $commentaire, $idAvis]);
    }

    public function isThisOpinionBelongToHim($idAvis){
        $requete = $this->getBdd()->prepare("SELECT COUNT(*) as Nb FROM avis where idAvis = ? AND idUtilisateur = ?");
        $requete->execute([$idAvis, $_SESSION['idUtilisateur']]);
        $infoAvis =  $requete->fetch(PDO::FETCH_ASSOC)['Nb'];
        if($infoAvis == 0){
            return false;
        }
        return true;
    }

    public function supAvis($idAvis){
        $requete = $this->getBdd()->prepare("CALL sup_avis(?)");
        $requete->execute([$idAvis]);
    }

    public function getAvisAsc($idHebergement){
        $requete = $this->getBdd()->prepare("SELECT * FROM avis inner join utilisateurs using(idUtilisateur) where idHebergement = ? order by note ASC");
        $requete->execute([$idHebergement]);
        $infoAvis =  $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infoAvis;
    }

    public function getAvisDesc($idHebergement){
        $requete = $this->getBdd()->prepare("SELECT * FROM avis inner join utilisateurs using(idUtilisateur) where idHebergement = ? order by note DESC");
        $requete->execute([$idHebergement]);
        $infoAvis =  $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infoAvis;
    }

    public function getAvisByDate($idHebergement){
        $requete = $this->getBdd()->prepare("SELECT * FROM avis inner join utilisateurs using(idUtilisateur) where idHebergement = ? order by avis.date DESC");
        $requete->execute([$idHebergement]);
        $infoAvis =  $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infoAvis;
    }

    public function getAverageAvis($idHebergement){
        $requete = $this->getBdd()->prepare("SELECT AVG(note) as average FROM `avis` WHERE idHebergement = ?");
        $requete->execute([$idHebergement]);
        $infoAvis =  $requete->fetch(PDO::FETCH_ASSOC);
        return round($infoAvis["average"], 1);
    }
}