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

    public function getHebergementEnAttente(){
        $requete = $this->getBdd()->prepare("SELECT avh.*, u.nom, u.prenom, v.idVille FROM admin_valid_hebergements avh inner join utilisateurs u using(IdUtilisateur) left join villes v on avh.nomVille = v.libelle where avh.is_actif = 1 order by id_admin_valid_hebergement");
        $requete->execute();
        $return = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $return;
    }

    public function getHebergementRef(){
        $requete = $this->getBdd()->prepare("SELECT avh.*, u.nom, u.prenom, v.idVille FROM admin_valid_hebergements avh inner join utilisateurs u using(IdUtilisateur) left join villes v on avh.nomVille = v.libelle where avh.is_actif = 0 order by id_admin_valid_hebergement");
        $requete->execute();
        $return = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $return;
    }

    public function supHebergementEnAttente($idDemande){
        $requete = $this->getBdd()->prepare("DELETE FROM admin_valid_hebergements where id_admin_valid_hebergement = ?");
        $requete->execute([$idDemande]);
    }

    public function refDemande($idDemande){
        $requete = $this->getBdd()->prepare("UPDATE admin_valid_hebergements set is_actif = 0 where id_admin_valid_hebergement = ?");
        $requete->execute([$idDemande]);
    }

    public function getHebergementByAskPicture(){
        $folder = scandir("../assets/src/tuuid");
        if(count($folder) > 2){
            
            $req = "";
            for($i = 2; $i < count($folder); $i++){
                $req .= $folder[$i] . ",";
            }
            $req = substr($req, 0, -1);
            $requete = $this->getBdd()->prepare("SELECT h.*, u.nom, u.prenom, v.libelle as libelleVille, r.libelle as libelleRegion FROM hebergement h inner join utilisateurs u using(IdUtilisateur) left join villes v using(idVille) left join regions r using(idRegion) where h.uuid in (?)");
            $requete->execute([$req]);
            $return = $requete->fetchALL(PDO::FETCH_ASSOC);
            return $return;
        } 
    }
}