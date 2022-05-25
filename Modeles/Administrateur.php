<?php

class Admin extends Utilisateur {

    public function getAllUsers(){
        $requete = $this->getBdd()->prepare("SELECT * FROM utilisateurs where actif = 1");
        $requete->execute();
        $AllUser = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $AllUser;
    }

    public function updateUser($email, $nom, $prenom, $age, $id){
        $requete = $this->getBdd()->prepare("UPDATE utilisateurs set email = ?, nom = ?, prenom = ?, DoB = ? where idUtilisateur = ?");
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
        $requete = $this->getBdd()->prepare("SELECT avh.*, u.nom, u.prenom, v.idVille, v.libelle as nomVille FROM hebergement avh inner join utilisateurs u using(IdUtilisateur) left join villes v using(idVille) where avh.actif = 0 order by idHebergement");
        $requete->execute();
        $return = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $return;
    }

    public function getHebergementRef(){
        $requete = $this->getBdd()->prepare("SELECT avh.*, u.nom, u.prenom, v.idVille, v.libelle as nomVille FROM hebergement avh inner join utilisateurs u using(IdUtilisateur) left join villes v using(idVille) where avh.actif = 0 order by idHebergement");
        $requete->execute();
        $return = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $return;
    }

    public function acceptHebergementEnAttente($idDemande, $uuid){
        $requete = $this->getBdd()->prepare("UPDATE hebergement set actif = 1, uuid = ? where idHebergement = ?");
        $requete->execute([$uuid, $idDemande]);
    }

    public function supHebergementEnAttente($idDemande){
        $requete = $this->getBdd()->prepare("DELETE FROM hebergement where idHebergement = ?");
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
                $req .= "'".$folder[$i]."'" . ",";
            }
            $req = substr($req, 0, -1);
            $requete = $this->getBdd()->prepare("SELECT h.*, u.nom, u.prenom, v.libelle as libelleVille, r.libelle as libelleRegion FROM hebergement h inner join utilisateurs u using(IdUtilisateur) left join villes v using(idVille) left join regions r using(idRegion) where h.uuid in ($req) and h.actif = 1");
            $requete->execute();
            $return = $requete->fetchAll(PDO::FETCH_ASSOC);
            return $return;
            
        }
    }
}