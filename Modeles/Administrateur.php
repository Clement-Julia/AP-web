<?php

class Admin extends Modele {

    private $idAdmin;
    private $email;
    private $mdp;
    private $nom;
    private $prenom;
    private $age;
    protected $idRole;

    public function __construct($idUtilisateur = null){

        if ( $idUtilisateur != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE idUtilisateur = ? and idRole = ?");
            $requete->execute([$idUtilisateur], 2);
            $infoUser =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idAdmin = $infoUser["idUtilisateur"];
            $this->email = $infoUser["email"];
            $this->mdp = $infoUser["mdp"];
            $this->nom = $infoUser["nom"];
            $this->prenom = $infoUser["prenom"];
            $this->age = $infoUser["age"];
            $this->idRole = $infoUser["idRole"];

        }
        
    }

    public function getIdAdmin(){
        return $this->idUtilisateur;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getMdp(){
        return $this->mdp;
    }

    public function getNom(){
        return $this->nom;
    }

    public function getPrenom(){
        return $this->prenom;
    }

    public function getAge(){
        return $this->age;
    }

    public function getIdRole(){
        return $this->idRole;
    }

}