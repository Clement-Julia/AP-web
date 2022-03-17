<?php
session_start();
session_destroy();
if(!empty($_COOKIE['connection_cookies'])){
    // Commence par supprimer la valeur du cookie
    unset($_COOKIE['connection_cookies']);
    // Puis désactive le cookie en lui fixant 
    // une date d'expiration dans le passé
    setcookie('connection_cookies', '', time() - 4200, '/');
}
header("location:../vues/");
?>