<?php

class Avis extends Modele {

    private $idAvis;
    private $date;
    private $note;
    private $commentaire;
    private $idUtilisateur;
    private $idHotel;

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
            $this->idHotel = $infoAvis["idHotel"];

        }
        
    }

    public function initialiserAvis($idAvis, $date, $note, $commentaire, $idUtilisateur, $idHotel){

        $this->idAvis = $idAvis;
        $this->date = $date;
        $this->note = $note;
        $this->commentaire = $commentaire;
        $this->idUtilisateur = $idUtilisateur;
        $this->idHotel = $idHotel;

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

    public function getIdHotel(){
        return $this->idHotel;
    }

    
    public function getAllAvis(){
        $requete = $this->getBdd()->prepare("SELECT hebergement.* FROM hebergement inner join avis using(idHebergement)");
        $requete->execute();
        $infoAvis =  $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infoAvis;
    }

    public function getHebergementbynonAvis($idUtilisateur){
        $requete = $this->getBdd()->prepare("SELECT DISTINCT hebergement.* FROM hebergement inner join reservations_hebergement using(idHebergement) inner join utilisateurs using(idUtilisateur) LEFT join avis using(idHebergement) where utilisateurs.idUtilisateur = ? and (avis.idUtilisateur is null or avis.idUtilisateur != utilisateurs.idUtilisateur)");
        $requete->execute([$idUtilisateur]);
        $infoAvis =  $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infoAvis;
    }
}