<?php

require_once "Modeles/Modele.php";
require_once "Modeles/Utilisateur.php";

use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase{

    public function testGetUsername() {
        $User = new Utilisateur();
        $User->setPrenom("Michel");
        $this->assertSame("Michel", $User->getPrenom());
    }
}