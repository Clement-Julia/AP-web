<?php

class Hebergement extends Modele {

    private $idHebergement;
    private $libelle;
    private $description;
    private $idVille;
    private $latitude;
    private $longitude;
    private $prix;
    private $options = [
        'television' => [
            'icon' => '<i class="fas fa-tv"></i>',
            'option' => ''
        ],
        'lave_linge' => [
            'icon' => '<i class="fas fa-sink"></i>',
            'option' => ''
        ],
        'seche_linge' => [
            'icon' => '<i class="fas fa-sink"></i>',
            'option' => ''
        ],
        'cuisine' => [
            'icon' => '<i class="fas fa-utensils"></i>',
            'option' => ''
        ],
        'refrigirateur' => [
            'icon' => '<i class="fas fa-icicles"></i>',
            'option' => ''
        ],
        'four' => [
            'icon' => '<i class="fab fa-hotjar"></i>',
            'option' => ''
        ],
        'parking_gratuit' => [
            'icon' => '<i class="fas fa-car"></i>',
            'option' => ''
        ],
        'linge_de_maison' => [
            'icon' => '<i class="fas fa-tshirt"></i>',
            'option' => ''
        ],
        'vaiselle' => [
            'icon' => '<i class="fas fa-utensils"></i>',
            'option' => ''
        ],
        'cafetiere' => [
            'icon' => '<i class="fas fa-coffee"></i>',
            'option' => ''
        ],
        'climatisation' => [
            'icon' => '<i class="fas fa-temperature-low"></i>',
            'option' => ''
        ]
    ];

    public function __construct($idHebergement = null){

        if ( $idHebergement != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM hebergement WHERE idHebergement = ?");
            $requete->execute([$idHebergement]);
            $infoHotel =  $requete->fetch(PDO::FETCH_ASSOC);

            $this->idHebergement = $infoHotel["idHebergement"];
            $this->libelle = $infoHotel["libelle"];
            $this->description = $infoHotel["description"];
            $this->idVille = $infoHotel["idVille"];
            $this->latitude = $infoHotel["latitude"];
            $this->longitude = $infoHotel["longitude"];
            $this->prix = $infoHotel["prix"];
            $this->options['television']['option'] = $infoHotel["television"];
            $this->options['lave_linge']['option'] = $infoHotel["lave_linge"];
            $this->options['seche_linge']['option'] = $infoHotel["seche_linge"];
            $this->options['cuisine']['option'] = $infoHotel["cuisine"];
            $this->options['refrigirateur']['option'] = $infoHotel["refrigirateur"];
            $this->options['four']['option'] = $infoHotel["four"];
            $this->options['parking_gratuit']['option'] = $infoHotel["parking_gratuit"];
            $this->options['linge_de_maison']['option'] = $infoHotel["linge_de_maison"];
            $this->options['vaiselle']['option'] = $infoHotel["vaiselle"];
            $this->options['cafetiere']['option'] = $infoHotel["cafetiere"];
            $this->options['climatisation']['option'] = $infoHotel["climatisation"];

        }
        
    }

    public function initialiserHebergement($idHebergement, $libelle, $description, $idVille, $latitude, $longitude, $prix){

        $this->idHebergement = $idHebergement;
        $this->libelle = $libelle;
        $this->description = $description;
        $this->idVille = $idVille;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->prix = $prix;

    }

    public function getIdHebergement(){
        return $this->idHebergement;
    }

    public function getLibelle(){
        return $this->libelle;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getIdVille(){
        return $this->idVille;
    }

    public function getLatitude(){
        return $this->latitude;
    }

    public function getLongitude(){
        return $this->longitude;
    }
    
    public function getPrix(){
        return $this->prix;
    }

    public function getOptions(){
        return $this->options;
    }

    public function countHotel(){
        $requete = $this->getBdd()->prepare("SELECT count(idHebergement) as nbr from hebergement");
        $requete->execute();
        $info_nbr = $requete->fetch(PDO::FETCH_ASSOC);
        return $info_nbr;
    }

    public function getAllHotel(){
        $requete = $this->getBdd()->prepare("SELECT * FROM hebergement");
        $requete->execute();
        $Allhotel = $requete->fetchALL(PDO::FETCH_ASSOC);
        return $Allhotel;
    }

    public function getHotelbyName($libelle){
        $requete = $this->getBdd()->prepare("SELECT * FROM hebergement WHERE libelle = ?");
        $requete->execute([$libelle]);
        $infoHotel = $requete->fetch(PDO::FETCH_ASSOC);
        return $infoHotel;
    }

    public function addHotel($libelle, $description, $idVille, $latitude, $longitude, $prix, $television, $lave_linge, $seche_linge, $cuisine, $refrigirateur, $four, $parking_gratuit, $linge_de_maison, $vaiselle, $cafetiere, $climatisation){

        $requete = $this->getBdd()->prepare("INSERT into hebergement(libelle, description, idVille, latitude, longitude, prix, television, lave_linge, seche_linge, cuisine, refrigirateur, four, parking_gratuit, linge_de_maison, vaiselle, cafetiere, climatisation) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

        $requete->execute([$libelle, $description, $idVille, $latitude, $longitude, $prix, $television, $lave_linge, $seche_linge, $cuisine, $refrigirateur, $four, $parking_gratuit, $linge_de_maison, $vaiselle, $cafetiere, $climatisation]);
    }

    public function updatehotel($libelle, $description, $idVille, $latitude, $longitude, $prix, $television, $lave_linge, $seche_linge, $cuisine, $refrigirateur, $four, $parking_gratuit, $linge_de_maison, $vaiselle, $cafetiere, $climatisation, $id){

        $requete = $this->getBdd()->prepare("UPDATE hebergement set libelle = ?, description = ?, idVille = ?, latitude = ?, longitude = ?, prix = ?, television = ?, lave_linge = ?, seche_linge = ?, cuisine = ?, refrigirateur = ?, four = ?, parking_gratuit = ?, linge_de_maison = ?, vaiselle = ?, cafetiere = ?, climatisation = ? where idHebergement = ?");

        $requete->execute([$libelle, $description, $idVille, $latitude, $longitude, $prix, $television, $lave_linge, $seche_linge, $cuisine, $refrigirateur, $four, $parking_gratuit, $linge_de_maison, $vaiselle, $cafetiere, $climatisation, $id]);
    }

    public function supHotel($libelle){
        $requete = $this->getBdd()->prepare("DELETE from hebergement where libelle = ?");
        $requete->execute([$libelle]);
    }
}