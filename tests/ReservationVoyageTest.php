<?php
require_once "Modeles/Modele.php";
require_once "Modeles/ReservationVoyage.php";

use PHPUnit\Framework\TestCase;

class ReservationVoyageTest extends TestCase
{

    public function testInsertBaseTravel(){
        $ReservationVoyage = new ReservationVoyage();
        $idReservation = $ReservationVoyage->insertBaseTravel(689.89, 9999, true);
        $this->assertStringMatchesFormat('%d', $idReservation);
        $this->assertGreaterThan(0, $idReservation);
    }

    public function testGetIdBuildingTravelByUserId(){
        $ReservationVoyage = new ReservationVoyage();
        $idReservationVoyage = $ReservationVoyage->getIdBuildingTravelByUserId(9999);
        $this->assertStringMatchesFormat('%d', $idReservationVoyage);
        $this->assertGreaterThan(0, $idReservationVoyage);
    }

    public function testUpdateTravelPrice(){
        $ReservationVoyage = new ReservationVoyage();
        $idReservationVoyage = $ReservationVoyage->getIdBuildingTravelByUserId(9999);
        $this->assertTrue($ReservationVoyage->updateTravelPrice(7777, $idReservationVoyage));
    }

    public function testDeleteBuildingTravelByUserId(){
        $ReservationVoyage = new ReservationVoyage();
        $this->assertTrue($ReservationVoyage->deleteBuildingTravelByUserId(9999));
    }

}