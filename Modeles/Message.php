<?php

class Message extends Modele {

    protected $idMessage;
    protected $date;
    protected $contenu;
    protected $expediteur;
    protected $destinataire;
    protected $role; // objet à construire

    public function __construct($idMessage = null){

        if ( $idMessage != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM messages WHERE idMessage = ?");
            $requete->execute([$idMessage]);
            $infoMessage = $requete->fetch(PDO::FETCH_ASSOC);

            $this->idMessage = $infoMessage["idMessage"];
            $this->date = $infoMessage["date"];
            $this->contenu = $infoMessage["contenu"];
            $this->expediteur = $infoMessage["expediteur"];
            $this->destinataire = $infoMessage["destinataire"];
            $this->role = $infoMessage["idRole"]; // objet à instancier

        }

    }

    public function initialiserMessages($idMessage, $date, $contenu, $expediteur, $destinataire, $idRole){

        $this->idMessage = $idMessage;
        $this->date = $date;
        $this->contenu = $contenu;
        $this->expediteur = $expediteur;
        $this->destinataire = $destinataire;
        $this->role = $idRole;

    }

    public function getIdMessage(){
        return $this->idMessage;
    }

    public function getDate(){
        return $this->date;
    }

    public function getContenu(){
        return $this->contenu;
    }

    public function getExpediteur(){
        return $this->expediteur;
    }

    public function getDestinataire(){
        return $this->destinataire;
    }

    public function getIdRole(){
        return $this->idRole;
    }

}

// récupération du nombre de messages non lu concernant l'utilisateur 

// function sqlMessagesUtilisateur($SESSION_idUtilisateur){

//     $requete = getBdd()->prepare("SELECT (COUNT(m.idMessage) - COUNT(c.idMessage)) AS nbMsg FROM messages m LEFT JOIN consulter_messages c USING(idMessage) WHERE idMessage IN (SELECT idMessage FROM messages WHERE destinataire = ?)");
//     $requete->execute([$SESSION_idUtilisateur]);
//     return $requete->fetch(PDO::FETCH_ASSOC);

// }

// récupération du nombre de messages non lu concernant l'admin (grace à l'idUtilisateur de la table consulter message qui sera null si non présent dans la table et donc non lu)

// function sqlMessagesAdmin($session_role){

//     $requete = getBdd()->prepare("SELECT (COUNT(m.idMessage) - COUNT(c.idMessage)) AS nbMsg FROM messages m LEFT JOIN consulter_messages c USING(idMessage) WHERE idMessage IN (SELECT idMessage FROM messages WHERE idRole = ?)");
//     $requete->execute([$session_role]);
//     return $requete->fetch(PDO::FETCH_ASSOC);

// }

// récupération des messages concernant l'utilisateur, aussi bien expéditeur que destinataire

// function sqlMessagerie($SESSION_idUtilisateur){

//     $requete = getBdd()->prepare("SELECT idMessage, date, contenu, expediteur, destinataire FROM messages WHERE expediteur = ? OR destinataire = ?");
//     $requete->execute([$SESSION_idUtilisateur, $SESSION_idUtilisateur]);
//     $messages = $requete->fetchALL(PDO::FETCH_ASSOC);
//     return $messages;
// }

// actualisation d'un message non lu vers un message lu

// function sqlMessageUserLu($SESSION_idUtilisateur){

//     $requete = getBdd()->prepare("SELECT idMessage FROM messages LEFT JOIN consulter_messages USING(idMessage) WHERE destinataire = ? and idUtilisateur IS NULL");
//     $requete->execute([$SESSION_idUtilisateur]);
//     $idMessages = $requete->fetchALL(PDO::FETCH_ASSOC);

//     $valeurs = [];
//     foreach($idMessages as $idMessage){
//         $valeurs[] = $SESSION_idUtilisateur;
//         $valeurs[] = $idMessage["idMessage"];
//     }

//     try{
//     $sql = "INSERT INTO consulter_messages(idUtilisateur, idMessage) VALUES " . substr(str_repeat("(?,?),", (count($valeurs) / 2)), 0, -1);
//     $requete = getBdd()->prepare($sql);
//     $requete->execute($valeurs);
//     } catch(Exception $e){

//     }

// }

// function sqlMessageAdminLu($GET_utilisateur, $SESSION_idUtilisateur){

//     $requete = getBdd()->prepare("SELECT idMessage FROM messages LEFT JOIN consulter_messages USING(idMessage) WHERE expediteur = ? and idUtilisateur IS NULL");
//     $requete->execute([$GET_utilisateur]);
//     $idMessages = $requete->fetchALL(PDO::FETCH_ASSOC);

//     $valeurs = [];
//     foreach($idMessages as $idMessage){
//         $valeurs[] = $SESSION_idUtilisateur;
//         $valeurs[] = $idMessage["idMessage"];
//     }

//     try{
//     $sql = "INSERT INTO consulter_messages(idUtilisateur, idMessage) VALUES " . substr(str_repeat("(?,?),", (count($valeurs) / 2)), 0, -1);
//     $requete = getBdd()->prepare($sql);
//     $requete->execute($valeurs);
//     } catch(Exception $e){

//     }

// }

// introduit le nouveau message dans la base de donnée

// function sqlNewMessageAdmin($POST_newMessage, $SESSION_idUtilisateur, $GET_utilisateur){

//     $requete = getBdd()->prepare("INSERT INTO messages(date, contenu, expediteur, destinataire, idRole) VALUES(NOW(), ?, ?, ?, (SELECT idRole FROM utilisateurs WHERE idUtilisateur = ?))");
//     $requete->execute([$POST_newMessage, $SESSION_idUtilisateur, $GET_utilisateur, $GET_utilisateur]);

// }

// function sqlNewMessageUser($POST_newMessage, $SESSION_idUtilisateur){

//     $requete = getBdd()->prepare("INSERT INTO messages(date, contenu, expediteur, idRole) VALUES(NOW(), ?, ?, ?)");
//     $requete->execute([$POST_newMessage, $SESSION_idUtilisateur, 2]);

// }