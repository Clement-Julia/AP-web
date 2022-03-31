<?php
require_once "Modeles/Modele.php";
require_once "Modeles/Utilisateur.php";

use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{

    public function testInscription(){
        $Utilisateur = new Utilisateur();
        $this->assertTrue($Utilisateur->inscription("testUnitaire@test.fr", "Kdo%test89T", "test", "test", "1991-06-18", 1, 1));
    }

    public function testConnexion(){
        $Utilisateur = new Utilisateur();
        $this->assertEquals(['success' => true, 'error' => 0], $Utilisateur->connexion("testUnitaire@test.fr", "Kdo%test89T"));
    }

    public function testCheckMdpFormat(){
        $Utilisateur = new Utilisateur();
        $this->assertEmpty($Utilisateur->check_mdp_format($_SESSION["mdp"]));
    }

    public function testEmailExiste(){
        $Utilisateur = new Utilisateur();
        $this->assertEquals(['email' => $_SESSION["email"]], $Utilisateur->emailExiste($_SESSION["email"]));
        
    }

    public function testGetAgeByDate(){
        $Utilisateur = new Utilisateur($_SESSION["idUtilisateur"]);
        $age = $Utilisateur->getAgeByDate();
        $this->assertStringMatchesFormat('%d', $age);
        $this->assertGreaterThanOrEqual(18, $age);
    }

    public function testCountUser(){
        $Utilisateur = new Utilisateur();
        $nbUtilisateurs = $Utilisateur->countUser()['nbr'];
        $this->assertStringMatchesFormat('%d', $nbUtilisateurs);
        $this->assertGreaterThanOrEqual(0, $nbUtilisateurs);
    }

    public function testGetAllUsers(){
        $Utilisateur = new Utilisateur();
        $this->assertGreaterThanOrEqual(1, Count($Utilisateur->GetAllUsers()));
    }

    public function testGetUserByEmail(){
        $Utilisateur = new Utilisateur();
        $this->assertEquals($_SESSION["email"], $Utilisateur->getUserByEmail($_SESSION["email"])['email']);
    }

    public function testDeleteUser(){
        $Utilisateur = new Utilisateur();
        $this->assertTrue($Utilisateur->deleteUser($_SESSION["idUtilisateur"]));
    }

}