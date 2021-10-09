<?php

class Api extends Modele {

    // la fonction de base qui ma me permettre l'envoi propre de donnée Json
    public function sendJSON($infos){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($infos,JSON_UNESCAPED_UNICODE);
    }

    public function getReservBetweenDate($date, $idHebergement){
        $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hebergement INNER JOIN reservations_voyages ON reservations_hebergement.idVoyage = reservations_voyages.idReservationVoyage WHERE (? BETWEEN dateDebut AND dateFin) AND idHebergement = ? AND is_building = ?");
        $requete->execute([$date, $idHebergement, 0]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    public function getOurOwnReserv($date, $idUtilisateur, $idReservationHebergement){
        $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hebergement INNER JOIN reservations_voyages ON reservations_hebergement.idVoyage = reservations_voyages.idReservationVoyage WHERE (? BETWEEN dateDebut AND dateFin) AND reservations_hebergement.idUtilisateur = ? AND idReservationHebergement != ? AND dateFin != ? AND is_building = ?");
        $requete->execute([$date, $idUtilisateur, $idReservationHebergement, $date, 1]);
        return $requete->fetch(PDO::FETCH_ASSOC);
    }

    // ça devrait être ailleurs (dans la classe région probablement)
    public function getInfosRegions($idRegion){
        $requete = $this->getBdd()->prepare("SELECT description FROM regions WHERE idRegion = ?");
        $requete->execute([$idRegion]);
        return $this->sendJSON($requete->fetch(PDO::FETCH_ASSOC));
    }

    public function getHebergementBooking($dateArrivee, $nbJours, $idHebergement){

        $boolean = false;
        $Hebergement = new Hebergement($idHebergement);
        $bookingDates = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement());

        for($i = 0; $i < $nbJours; $i++){

            $date = new DateTime($dateArrivee . '+' . $i . 'days');

            if(in_array($date->format("Y-m-d"), $bookingDates)){
                $boolean = true;
            }
        }

        if($boolean){
            $return['message'] = "La réservation n'est pas disponible, un autre utilisateur ayant déjà réservé une partie des dates que vous souhaitiez";
            $return['code'] = 402;
        } else {
            $return['code'] = 200;
        }

        $this->sendJSON($return);
    }

    // Fonction en lien avec le FETCH de changeDate.php (l'asynchrone qui dit en JS à l'utilisateur si c'est libre ou non mais aussi s'il écourte ou écrase une partie du voyage qu'il est en train de créer)
    function getValidity($da, $nbj, $idreser){

        if (
            !empty($da) && 
            isValidDate($da) &&
            !empty($nbj) && 
            is_numeric($nbj) &&
            !empty($idreser) &&
            is_numeric($idreser)
        ){

            $ReservationHebergement = new ReservationHebergement($idreser);
            $Hebergement = new Hebergement($ReservationHebergement->getIdHebergement());
            $Api = new Api();

            if($_SESSION['idUtilisateur'] == $ReservationHebergement->getIdUtilisateur()){

                // On va boucler sur chacun des jours afin de vérifier si un voyageur autre que nous a déjà réservé sur ces dates
                $mesReservations = [];

                for($i = 0; $i < $nbj; $i++){

                    $date = new DateTime($da . '+' . $i . ' days');
                    $booking = $Api->getReservBetweenDate($date->format("Y-m-d"), $Hebergement->getIdHebergement());
                    if(!empty($booking) && $booking['dateFin'] != $date->format("Y-m-d")){
                        // Si on entrer ici, un hébergement est déjà réservé par un autre utilisateur
                        $return['message'] = "Un autre voyageur à déjà réservé l'hébergement sur tout ou partie de la période choisie";
                        $return['code'] = 402;

                        $this->sendJSON($return);
                        exit;

                    }

                    $reserve = $Api->getOurOwnReserv($date->format("Y-m-d"), $_SESSION['idUtilisateur'], $_SESSION['idReservationHebergement'], $date->format("Y-m-d"));
                    
                    if (!empty($reserve) && !in_array($reserve, $mesReservations)){
                        $mesReservations[] = $reserve;
                    }
                    
                }

                if(count($mesReservations) > 1){

                    $return['message'] = "Attention, les dates que vous avez choisi vont réduire ou complètement écraser vos réservations concernant les hébergements suivant : <br>";
                    foreach($mesReservations as $item){
                        $ownHebergement = new Hebergement($item['idHebergement']);
                        $ReservationTemp = new ReservationHebergement(($item['idReservationHebergement']));
                        $return['message'] .= "_ " . $ownHebergement->getLibelle() . " situé à " . $ownHebergement->getVilleLibelle($ownHebergement->getIdVille()) . " avec une arrivée prévue le " . $ReservationTemp->getDateDebut() . " et un départ le " . $ReservationTemp->getDateFin() . "<br>";
                        $return['hebergement'][] = [
                            $ownHebergement->getLibelle(),
                            $ownHebergement->getVilleLibelle($ownHebergement->getIdVille()),
                            $ReservationTemp->getDateDebut(),
                            $ReservationTemp->getDateFin()
                        ];
                    }
                    substr($return['message'], 0, -2);
                    $return['code'] = 401;
        
                } else if(count($mesReservations) == 1){
        
                    $ownHebergement = new Hebergement($mesReservations[0]['idHebergement']);
                    $ReservationTemp = new ReservationHebergement(($mesReservations[0]['idReservationHebergement']));
                    $return['message'] = "Attention, les dates que vous avez choisi vont réduire ou complètement écraser votre réservations de l'hebergement : <br> _ " . $ownHebergement->getLibelle() . " situé à " . $ownHebergement->getVilleLibelle($ownHebergement->getIdVille()) . " avec une arrivée prévue le " . $ReservationTemp->getDateDebut() . " et un départ le " . $ReservationTemp->getDateFin();
                    $return['hebergement'][] = [
                        $ownHebergement->getLibelle(),
                        $ownHebergement->getVilleLibelle($ownHebergement->getIdVille()),
                        $ReservationTemp->getDateDebut(),
                        $ReservationTemp->getDateFin()
                    ];
                    $return['code'] = 401;
        
                } else {
                    $return['message'] = "Tout semble ok";
                    $return['code'] = 200;
                }
        
                $this->sendJSON($return);

            }
        }
    }

    // On supprime ou on ajoute un favoris entre un hébergement et un utilisateur
    public function insertOrDeleteLikeHebergement($idHebergement, $idUtilisateur){
        $Favoris = new Favoris($idHebergement, $idUtilisateur);
        if($Favoris->getIdHebergement() == null){
            $requete = $this->getBdd()->prepare("INSERT INTO favoris VALUE (?,?)");
            $requete->execute([$idHebergement, $idUtilisateur]);
            $this->sendJSON(['status' => 'added']);
        } else {
            $requete = $this->getBdd()->prepare("DELETE FROM favoris WHERE idHebergement = ? AND idUtilisateur = ?");
            $requete->execute([$idHebergement, $idUtilisateur]);
            $this->sendJSON(['status' => 'deleted']);
        }
    }

}