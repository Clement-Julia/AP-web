<?php
require_once "Modeles/Modele.php";
require_once "Modeles/Ville.php";

use PHPUnit\Framework\TestCase;

class VilleTest extends TestCase
{

    public function testAddVille(){
        $Ville = new Ville();
        // On génère un uuid unique avec création de dossier à la suite
        $nom_doss = bin2hex(random_bytes(32));
        while(file_exists("assets/src/uuid/".$nom_doss)){
            $nom_doss = bin2hex(random_bytes(32));
        }
        mkdir("assets/src/uuid/".$nom_doss, 0700);

        $this->assertTrue($Ville->addVille("laBelleVilleDesPaysDeLaLoire", 47.0513, -0.513264, 44315, 2, "Une très belle ville du pays de la loire", $nom_doss));

        $_SESSION['uuid'] = $nom_doss;
        $_SESSION['idVille'] = $Ville->getVillebyUuid($_SESSION['uuid'])['idVille'];
    }

    public function testGetRegion(){
        $Ville = new Ville();
        $this->assertNotNull($Ville->getRegion($_SESSION['idVille']));
    }

    public function testCountVille(){
        $Ville = new Ville();
        $nbVilles = $Ville->countVille()['nbr'];
        $this->assertStringMatchesFormat('%d', $nbVilles);
        $this->assertGreaterThanOrEqual(0, $nbVilles);
    }

    public function testGetAllVille(){
        $Ville = new Ville();
        $this->assertGreaterThanOrEqual(1, Count($Ville->getAllville()));
    }

    public function testUpdateVille(){
        $Ville = new Ville();
        $Ville->setLibelle("laBelleVilleDesPaysDeLaLoire");
        $Ville->setLatitude(47.0513);
        $Ville->setLongitude(-0.513264);
        $Ville->setCode_postal(44315);
        $Ville->setIdRegion(2);
        $Ville->setDescription("Une très belle ville du pays de la loire");

        $this->assertTrue($Ville->updateVille($Ville->getLibelle(), $Ville->getLatitude(), $Ville->getLongitude(), $Ville->getCode_postal(), $Ville->getIdRegion(), $Ville->getDescription(), $_SESSION['uuid'], $_SESSION['idVille']));
    }

    public function testGetFreeHebergement(){
        $Ville = new Ville();
        $now = new DateTime('now');
        $this->assertIsArray($Ville->getFreeHebergement($now->format('Y-m-d'), $_SESSION['idVille']));
    }

    public function testSupVille(){
        $Ville = new Ville();
        $this->assertTrue($Ville->supVille($_SESSION['idVille']));
        rmdir("assets/src/uuid/".$_SESSION['uuid']);
    }

}