<?php
require_once "Modeles/Modele.php";
require_once "Modeles/Activite.php";

use PHPUnit\Framework\TestCase;

class ActiviteTest extends TestCase
{

    public function testAddActiviteForCity(){
        $Activite = new Activite();
        $this->assertTrue($Activite->addActiviteForCity(9999, 9999, 48.5621, -0.5412, "Une superbe activité"));
    }

    public function testupdateActiviteForCity(){
        $Activite = new Activite();
        $this->assertTrue($Activite->updateActiviteForCity(9999, 9999, 48.5621, -0.5412, "Une superbe activité", 49, -0.5));
    }

    public function testdeleteActiviteForCity(){
        $Activite = new Activite();
        $this->assertTrue($Activite->deleteActiviteForCity(9999, 9999, 48.5621, -0.5412));
    }

    public function testGetAllActiviteByVille(){
        $Activite = new Activite();
        $this->assertIsArray($Activite->getAllActiviteByVille());
    }

    public function testGetAllActivite(){
        $Activite = new Activite();
        $this->assertIsArray($Activite->getAllActivite());
    }

}