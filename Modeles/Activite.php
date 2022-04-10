<?php

class Activite extends Modele {

    private $idActivite;
    private $libelle;
    private $icon;

    public function __construct($idActivite = null){

        if ( $idActivite != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM activites WHERE idActivite = ?");
            $requete->execute([$idActivite]);
            $infoActivite =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idActivite = $infoActivite["idActivite"];
            $this->libelle = $infoActivite["libelle"];
            $this->icon = $infoActivite["icon"];

        }
        
    }

    public function initialiserActivite($idActivite, $libelle, $icon){

        $this->idActivite = $idActivite;
        $this->adlibelleesse = $libelle;
        $this->icon = $icon;

    }

    public function initialiserActiviteByVille($idActivite, $libelle, $icon, $latitude, $longitude, $description){

        $this->idActivite = $idActivite;
        $this->adlibelleesse = $libelle;
        $this->icon = $icon;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->description = $description;

    }

    public function getIdActivite(){
        return $this->idActivite;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function getIcon(){
        return $this->icon;
    }

    public function getAllActivite(){
        $requete = $this->getBdd()->prepare("SELECT * FROM activites");
        $requete->execute();
        $infos = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infos;
    }

    public function getAllActiviteByVille(){
        $requete = $this->getBdd()->prepare("SELECT * FROM activites_by_ville");
        $requete->execute();
        $infos = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $infos;
    }

    public function getActiviteForVilleByName($name){
        $requete = $this->getBdd()->prepare("SELECT * FROM activites_by_ville where description = ?");
        $requete->execute([$name]);
        $infos = $requete->fetch(PDO::FETCH_ASSOC);
        return $infos;
    }

    public function getActiviteByName($name){
        $requete = $this->getBdd()->prepare("SELECT * FROM activites where libelle = ?");
        $requete->execute([$name]);
        $info = $requete->fetch(PDO::FETCH_ASSOC);
        return $info;
    }

    public function addActiviteForCity($idActivite, $idVille, $latitude, $longitude, $description){
        try {
            $requete = $this->getBdd()->prepare("INSERT into activites_by_ville(idActivite, idVille, latitude, longitude, description) values(?,?,?,?,?)");
            $requete->execute([$idActivite, $idVille, $latitude, $longitude, $description]);
        } catch (Exception $e){
            return false;
        }
        return true;
    }

    public function updateActiviteForCity($idActivite, $idVille, $latitude, $longitude, $description, $oldLatitude, $oldLongitude){
        try{
            $requete = $this->getBdd()->prepare("UPDATE activites_by_ville set idActivite = ?, idVille = ?, latitude = ?, longitude = ?, description = ? where latitude like ? and longitude like ?");
            $requete->execute([$idActivite, $idVille, $latitude, $longitude, $description, $oldLatitude, $oldLongitude]);
        } catch (Exception $e){
            return false;
        }
        return true;
    }

    public function deleteActiviteForCity($idActivite, $idVille, $latitude, $longitude){
        try {
            $requete = $this->getBdd()->prepare("DELETE FROM activites_by_ville WHERE (latitude = ? AND longitude = ?)");
            $requete->execute([$idActivite, $idVille, $latitude, $longitude]);
        } catch (Exception $e){
            return false;
        }
        return true;
    }

}