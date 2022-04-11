<?php
require_once "traitement.php";
$user = new Utilisateur();

if($_SESSION["idRole"] == 2){
    if($_GET["ban"] == "user"){
        try{
            $user->BanUser($_GET["id"]);
            header("location:../admin/modifUser.php?success");
        }catch(exception $e){
            header("location:../admin/modifUser.php?crash");
        }
    }elseif($_GET["ban"] == "ip"){
        try{
            $user->BanIp($_GET["ip"]);
            header("location:../admin/logUser.php?success");
        }catch(exception $e){
            echo $e; exit;
            header("location:../admin/logUser.php?crash");
        }
    }else{
        header("location:../");
    }
}else{
    header("location:../");
}
