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
            $admin->BanIp($_GET["ip"]);
            header("location:../admin/modifUser.php?success");
        }catch(exception $e){
            header("location:../admin/modifUser.php?crash");
        }
    }else{
        header("location:../admin/modifUser.php?crash");
    }
}else{
    header("location:../");
}
