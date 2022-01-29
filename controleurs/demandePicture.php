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
            rename("../assets/src/tuuid/".$hotel->getUuid()."/".$hotel->getLibelle().$i.$ext, "../assets/src/uuid/".$hotel->getUuid()."/".$hotel->getLibelle().$lim.$ext);

            $first ++;
            $lim ++;
        }
        rmdir("../assets/src/tuuid/".$hotel->getUuid());
        
        header("location:../admin/validPicture.php?success=acc");

    }catch(exception $e){
        header("location:../admin/validPicture.php?error");
    }

}elseif($_GET["command"] == "ref"){
    try{

        rmdir("../assets/src/tuuid/".$hotel->getUuid());
        header("location:../admin/validPicture.php?success=ref");

    }catch(exception $e){
        header("location:../admin/validPicture.php?error");
    }
}