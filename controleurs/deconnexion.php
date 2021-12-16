<?php
session_start();
session_destroy();
if(!empty($_COOKIE['connection_cookies'])){
    // on demande au navigateur de supprimer le cookie
    setcookie('connection_cookies');
    // on supprimer bien la variable cookie
    unset($_COOKIE['connection_cookies']);
}
header("location:../vues/");
?>