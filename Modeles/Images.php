<?php

class Images extends Modele {

    private $uuid;
    private $libelle;

    public function __construct($uuid = null){

        if ( $uuid != null ){

            $requete = $this->getBdd()->prepare("SELECT * FROM villes WHERE uuid = ?");
            $requete->execute([$uuid]);
            $libelle = $requete->fetch(PDO::FETCH_ASSOC)['libelle'];

            if($libelle == null){
                $requete = $this->getBdd()->prepare("SELECT * FROM hebergement WHERE uuid = ?");
                $requete->execute([$uuid]);
                $libelle = $requete->fetch(PDO::FETCH_ASSOC)['libelle'];
            }

            $this->uuid = $uuid;
            $this->libelle = $libelle;
        }
        
    }

    public function getAllPathImages(){
        $path = "../assets/src/uuid/" . $this->uuid . "/" . "*.*";
        $filenames = glob($path);
        return $filenames;
    }

    public function getBanniere(){
        $path = "../assets/src/uuid/" . $this->uuid . "/" . "banniere.*";
        $filename = glob($path);

        if(count($filename) == 0){
            return "../assets/src/img/default-hotel.jpg";
        } else {
            return $filename[0];
        }
    }

    public function getImageDescriptionHebergementCode(){

        $path = "../src/uuid/" . $this->uuid . "/" . $this->libelle ."*.*";
        $filenames = glob($path);

        if(count($filenames) == 0){
            ob_start(); ?>
            <div id="hd-pictures">
                <div class="only-one-img">
                    <img src="../assets/src/img/default-hotel.jpg" alt="photo de l'hébergement" class="img-fluid">
                </div>
            </div>
            <?php
        } else if(count($filenames) == 1){

            ob_start(); ?>
            <div id="hd-pictures">
                <div class="only-one-img">
                    <img src="<?=$filenames[0]?>" alt="photo de l'hébergement" class="img-fluid">
                </div>
            </div>
            <?php

        } else if(count($filenames) == 2){

            ob_start(); ?>
            <div id="hd-pictures">
                <div class="two-img">
                    <img src="<?=$filenames[0]?>" alt="photo de l'hébergement" class="img-fluid">
                </div>
                <div class="two-img">
                    <img src="<?=$filenames[1]?>" alt="photo de l'hébergement" class="img-fluid">
                </div>
            </div>
            <?php

        } else if(count($filenames) == 3){

            ob_start(); ?>
            <div id="hd-pictures">
                        <div id="big-img">
                            <img src="<?=$filenames[0]?>" alt="photo de l'hébergement" class="img-fluid">
                        </div>
                        <div id="container-little-img">
                            <div class="second-div-img .three-img">
                                <img src="<?=$filenames[1]?>" alt="photo de l'hébergement" class="img-fluid">   
                            </div>
                            <div class="second-div-img">
                                <img src="<?=$filenames[2]?>" alt="photo de l'hébergement" class="img-fluid">
                            </div>
                        </div>
                    </div>
            <?php
            return ob_get_clean();

        } else if(count($filenames) == 4){

            ob_start(); ?>
            <div id="hd-pictures">
                <div id="big-img">
                    <img src="<?=$filenames[0]?>" alt="photo de l'hébergement" class="img-fluid">
                </div>
                <div id="container-little-img">
                    <div class="second-div-img">
                        <div class="third-div-img">
                            <img src="<?=$filenames[1]?>" alt="photo de l'hébergement" class="img-fluid">   
                        </div>
                        <div class="third-div-img">
                            <img src="<?=$filenames[2]?>" alt="photo de l'hébergement" class="img-fluid radius-top-right">
                        </div>
                    </div>
                    <div class="second-div-img">
                        <img src="<?=$filenames[3]?>" alt="photo de l'hébergement" class="img-fluid">
                    </div>
                </div>
            </div>
            <?php
            return ob_get_clean();

        } else if(count($filenames) > 4){

            ob_start(); ?>
            <div id="hd-pictures">
                        <div id="big-img">
                            <img src="<?=$filenames[0]?>" alt="photo de l'hébergement" class="img-fluid">
                        </div>
                        <div id="container-little-img">
                            <div class="second-div-img">
                                <div class="third-div-img">
                                    <img src="<?=$filenames[1]?>" alt="photo de l'hébergement" class="img-fluid">   
                                </div>
                                <div class="third-div-img">
                                    <img src="<?=$filenames[2]?>" alt="photo de l'hébergement" class="img-fluid radius-top-right">
                                </div>
                            </div>
                            <div class="second-div-img">
                                <div class="third-div-img">
                                    <img src="<?=$filenames[3]?>" alt="photo de l'hébergement" class="img-fluid">
                                </div>
                                <div class="third-div-img">
                                    <img src="<?=$filenames[4]?>" alt="photo de l'hébergement" class="img-fluid radius-bottom-right">
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
            return ob_get_clean();
        }
        


    }

}