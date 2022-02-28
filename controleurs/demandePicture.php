<?php
require_once "traitement.php";
$admin = new Admin();
$hotel = new Hebergement($_GET["id"]);


if($_GET["command"] == "acc"){

    try{
        $lim = count(scandir("../assets/src/uuid/".$hotel->getUuid())) - 1;
        $folder = scandir("../assets/src/tuuid/".$hotel->getUuid());

        for($i = 2; $i < count($folder); $i++){
            $ext = substr($folder[$i], strrpos($folder[$i], '.'));
            if(strtok($folder[$i], '.') != "banniere"){
                rename("../assets/src/tuuid/".$hotel->getUuid()."/".$folder[$i], "../assets/src/uuid/".$hotel->getUuid()."/".$hotel->getLibelle().$lim.$ext);
            }else{
                rename("../assets/src/tuuid/".$hotel->getUuid()."/".$folder[$i], "../assets/src/uuid/".$hotel->getUuid()."/banniere".$ext);
            }

            $lim ++;
        }
        rmdir("../assets/src/tuuid/".$hotel->getUuid());
        
        header("location:../admin/validPicture.php?success=acc");

    }catch(exception $e){
        header("location:../admin/validPicture.php?error");
    }

}elseif($_GET["command"] == "ref"){
    try{
        $folder = scandir("../assets/src/tuuid/".$hotel->getUuid());
        for($i = 2; $i < count($folder); $i++){
            unlink("../assets/src/tuuid/".$hotel->getUuid()."/".$folder[$i]);
        }
        rmdir("../assets/src/tuuid/".$hotel->getUuid());
        header("location:../admin/validPicture.php?success=ref");

    }catch(exception $e){
        header("location:../admin/validPicture.php?error");
    }
}