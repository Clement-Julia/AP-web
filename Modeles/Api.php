<?php

class Api extends Modele {

    function getValidity($da, $nbj, $idreser){

        $Reservation = new ReservationHebergement($idreser);
        $Hebergement = new Hebergement($Reservation->getIdHebergement());
        $bookingDates = $Hebergement->getWhenHebergementIsBooking($Hebergement->getIdHebergement());

        $boolean = false;
        $verdict = [];

        for($i = 0; $i < $nbj; $i++){

            $date = new DateTime($da . '+' . $i . 'days');

            if(in_array($date->format("Y-m-d"), $bookingDates)){

                $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hebergement WHERE (? BETWEEN dateDebut AND dateFin) AND idHebergement = ?");
                $requete->execute([$date->format("Y-m-d"), $Hebergement->getIdHebergement()]);
                $reservations = $requete->fetchAll(PDO::FETCH_ASSOC);

                if(!empty($reservations)){
                    
                    foreach($reservations as $reservation){
                        if($reservation['idUtilisateur'] != $_SESSION['idUtilisateur']){
                            $boolean = true;
                        }
                        if($boolean){
                            $return['message'] = "Un autre voyageur à déjà réservé l'hébergement sur tout ou partie de la période choisie";
                            $return['code'] = 402;
                            break;
                        } else {
                            if(!in_array($reservation, $verdict)){
                                $verdict[] = $reservation;
                            }
                        }
                    }
                }
            }
        }

        if(count($verdict) > 1){

            $return['message'] = "Attention, les dates que vous avez choisi vont réduire ou complètement écraser vos réservations concernant les hébergements suivant : ";
            print_r($verdict);
            foreach($verdict as $item){
                $ownHebergement = new Hebergement($item['idHebergement']);
                $return['message'] .= $ownHebergement->getLibelle() . ", ";
            }
            substr($return['message'], 0, -2);
            $return['code'] = 401;

        } else if(count($verdict) == 1){

            $ownHebergement = new Hebergement($verdict[0]['idHebergement']);
            $return['message'] = "Attention, les dates que vous avez choisi vont réduire ou complètement écraser votre réservations de l'hebergement : " . $ownHebergement->getLibelle();
            $return['code'] = 401;

        } else if(!$boolean) {
            $return['message'] = "Tout semble ok";
            $return['code'] = 200;
        }

        $this->sendJSON($return);

    }

    public function sendJSON($infos){
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        echo json_encode($infos,JSON_UNESCAPED_UNICODE);
    }

}