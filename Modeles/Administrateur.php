<?php

class Admin extends Utilisateur {

    public function getAllUsers(){
        $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs");
        $requete->execute();
        $AllUser = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $AllUser;
    }

    public function updateUser($email, $nom, $prenom, $age, $id){
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs set email = ?, nom = ?, prenom = ?, age = ? where idUtilisateur = ?");
        $requete->execute([$email, $nom, $prenom, $age, $id]);
    }

    public function updateUser_info($nom, $prenom, $id){
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs set nom = ?, prenom = ? where idUtilisateur = ?");
        $requete->execute([$nom, $prenom, $id]);
    }

    public function updateUser_email($email, $id){
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs set email = ? where idUtilisateur = ?");
        $requete->execute([$email, $id]);
    }

    public function updateUser_mdp($mdp, $id){
        $mdp = password_hash($mdp, PASSWORD_BCRYPT);
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs set mdp = ? where idUtilisateur = ?");
        $requete->execute([$mdp, $id]);
    }

    public function supUser($id){
        $requete = $this->getBdd()->prepare("call sup_user(?)");
        $requete->execute([$id]);
    }
}