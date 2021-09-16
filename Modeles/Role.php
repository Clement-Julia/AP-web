<?php

class Role extends Utilisateur{

    private $idRole;
    private $libelle;

    public function __construct($idRole = null)
    {
        if ($idRole != null)
        {
            $requete = $this->getBdd()->prepare("SELECT * FROM roles WHERE idRole = ?");
            $requete->execute([$idRole]);
            $infosRole = $requete->fetch(PDO::FETCH_ASSOC);

            $this->idRole = $infosRole["idRole"];
            $this->libelle= $infosRole["libelle"];
        }
    }

    public function initializeRole($idRole)
    {
        $requete = $this->getBdd()->prepare("SELECT libelle FROM roles WHERE idRole = ?;");
        $requete->execute([$idRole]);
        $infosRole = $requete->fetch(PDO::FETCH_ASSOC);

        $this->idRole = $infosRole["idRole"];
        $this->libelle= $infosRole["libelle"];
    }

    public function getIdRole(){
        return $this->idRole;
    }

    public function getLibelle(){
        return $this->libelle;
    }
}