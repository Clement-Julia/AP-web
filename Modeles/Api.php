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
                $reservation = $requete->fetch(PDO::FETCH_ASSOC);

                if(!empty($reservation)){
                    
                    if($reservation['idUtilisateur'] != $_SESSION['idUtilisateur']){
                        $boolean = true;
                        $return['message'] = "Un autre voyageur à déjà réservé l'hébergement sur tout ou partie de la période choisie";
                        $return['code'] = 402;
                        $this->sendJSON($return);
                        exit;
                    }
                    if(!$boolean){
                        // il faudrait comparer ici si la date choisi correspond à une réservation, et la ça marche pas comme ça, car si pas de réservation sur cet hotel, bah ca teste pas si on écrase un de nos autres voyages à nous
                        
                        if(!in_array($reservation, $verdict)){
                            $verdict[] = $reservation;
                        }
                    }
                }
            }

            $requete = $this->getBdd()->prepare("SELECT * FROM reservations_hebergement WHERE (? BETWEEN dateDebut AND dateFin) AND idUtilisateur = ? AND idReservationHebergement != ? AND dateFin != ?");
            $requete->execute([$date->format("Y-m-d"), $_SESSION['idUtilisateur'], $idreser, $date->format("Y-m-d")]);
            $reserve = $requete->fetch(PDO::FETCH_ASSOC);
            
            if (!empty($reserve) && !in_array($reserve, $verdict)){
                $verdict[] = $reserve;
            }

        }

        if(count($verdict) > 1){

            $return['message'] = "Attention, les dates que vous avez choisi vont réduire ou complètement écraser vos réservations concernant les hébergements suivant : <br>";
            foreach($verdict as $item){
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

        } else if(count($verdict) == 1){

            $ownHebergement = new Hebergement($verdict[0]['idHebergement']);
            $ReservationTemp = new ReservationHebergement(($verdict[0]['idReservationHebergement']));
            $return['message'] = "Attention, les dates que vous avez choisi vont réduire ou complètement écraser votre réservations de l'hebergement : <br> _ " . $ownHebergement->getLibelle() . " situé à " . $ownHebergement->getVilleLibelle($ownHebergement->getIdVille()) . " avec une arrivée prévue le " . $ReservationTemp->getDateDebut() . " et un départ le " . $ReservationTemp->getDateFin();
            $return['hebergement'][] = [
                $ownHebergement->getLibelle(),
                $ownHebergement->getVilleLibelle($ownHebergement->getIdVille()),
                $ReservationTemp->getDateDebut(),
                $ReservationTemp->getDateFin()
            ];
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