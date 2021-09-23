<?php

class Utilisateur extends Modele {

    private $idUtilisateur;
    private $email;
    private $mdp;
    private $nom;
    private $prenom;
    private $age;
    protected $idRole;
    private $messages = [];
    private $avis = [];

    public function __construct($idUtilisateur = null){

        if ( $idUtilisateur != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE idUtilisateur = ?");
            $requete->execute([$idUtilisateur]);
            $infoUser =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idUtilisateur = $infoUser["idUtilisateur"];
            $this->email = $infoUser["email"];
            $this->mdp = $infoUser["mdp"];
            $this->nom = $infoUser["nom"];
            $this->prenom = $infoUser["prenom"];
            $this->age = $infoUser["age"];
            $this->idRole = $infoUser["idRole"];
            
            $requete = $this->getBdd()->prepare("SELECT * FROM messages WHERE expediteur = ? OR destinataire = ? ORDER BY date");
            $requete->execute([$idUtilisateur, $idUtilisateur]);
            $infosMessages = $requete->fetchAll(PDO::FETCH_ASSOC);

            foreach ( $infosMessages as $infoMessage ){
                $objetMessage = new Message();
                $objetMessage->initialiserMessages($infoMessage["idMessage"], $infoMessage["date"], $infoMessage["contenu"], $infoMessage["expediteur"], $infoMessage["destinataire"], $infoMessage["idRole"]);
                $this->messages[] = $objetMessage;
            }

        }
        
    }

    public function inscription($email, $mdp, $nom, $prenom, $age, $idRole){

        $mdp = password_hash($mdp, PASSWORD_BCRYPT);
        $requete = $this->getBdd()->prepare("INSERT INTO utilisateurs(email, mdp, nom, prenom, age, idRole) VALUES (?, ?, ?, ?, ?, ?);");
        $requete->execute([$email, $mdp, $nom, $prenom, $age, $idRole]);

    }

    public function connexion($email, $mdp){ 
        
        $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $requete->execute([$email]);

        if($requete->rowCount() > 0){

            $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);
            
            if(!password_verify($mdp, $utilisateur["mdp"])){
                $return["success"] = false;
                $return["error"] = 1;
            }else{

                $this->idUtilisateur = $utilisateur["idUtilisateur"];
                $this->idRole = $utilisateur["idRole"];
                $this->email = $utilisateur["email"];
                $this->nom = $utilisateur["nom"];
                $this->prenom = $utilisateur["prenom"];
                $_SESSION["idUtilisateur"] = $this->getIdUtilisateur();
                $_SESSION["nom"] = $this->getNom();
                $_SESSION["prenom"] = $this->getPrenom();
                $_SESSION["idRole"] = $this->getIdRole();
                $_SESSION["email"] = $this->getEmail();
                $_SESSION["mdp"] = $mdp;
            }


        }
        return $return;
    }

    // vérification si l'email est déjà présent dans la base de donnée
    public function emailExiste($email){

        $requete = $this->getBdd()->prepare("SELECT email FROM utilisateurs WHERE email = ?;");
        $requete->execute([$email]);
        $emailExist = $requete->fetch(PDO::FETCH_ASSOC)->rowCount();

        return $emailExist > 0 ? true : false;

    }

    public function getIdUtilisateur(){
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

    public function getMessages(){
        return $this->messages;
    }

    public function countUser(){
        $requete = $this->getBdd()->prepare("SELECT count(idUtilisateur) as nbr from utilisateurs");
        $requete->execute();
        $info_nbr = $requete->fetch(PDO::FETCH_ASSOC);
        return $info_nbr;
    }

}