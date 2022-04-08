<?php

function sortFunction( $a, $b ) {
    return strtotime($a) - strtotime($b);
}

function sortFunctionDate( $a, $b ) {
    return $a['dateDebut'] <=> $b['dateDebut'];
}

function isValidDate($date, $format = 'Y-m-d'){
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) === $date;
}

function lister_images($repertoire){
    $i = 1;
    if(is_dir($repertoire)){  
        if($iteration = opendir($repertoire)){  
            while(($fichier = readdir($iteration)) !== false){  
                if($fichier != "." && $fichier != ".."){
                    $fichier_info = finfo_open(FILEINFO_MIME_TYPE);
                    $mime_type = finfo_file($fichier_info, $repertoire."/".$fichier);
                    if(strpos($mime_type, 'image/') === 0){
                        if($fichier == "banniere.png" || $fichier == "banniere.jpg" || $fichier == "banniere.jpeg"){
                            $bool = 1;
                        }else{
                            $img = resizePicture($repertoire."/".$fichier, 200, 200);
                            echo
                            '<img src="'.$repertoire."/".$fichier.'"id="img'.$i.'" name="'.$repertoire."/".$fichier.'" class="rounded badgetest fluid-picture" width="400" height="400">' . 
                            '<button type="button" id="'.$i.'" style="visibility: hidden" onclick="supImage(this.id)">
                                <span class="badge badge-danger rounded position-badge" style="visibility: visible"><i class="fas fa-trash-alt"></i></span>
                            </button>';
                            $i++;
                        }
                    }
                }  
            }  
            closedir($iteration);  
        }  
    }
}

function coupe_phrase($string, $limit = 100, $fin= '...')
{
    if (mb_strlen($string) <= $limit) {
        return $string;
    }
    return rtrim(mb_substr($string, 0, $limit, 'UTF-8')) . $fin;
}

function error(){
    if(!empty($_GET["error"]) && $_GET["error"] == "crash"){
        ?>
        <div class="container alert alert-danger mt-3">
            <p>
                La fonctionnalité est actuellement indisponible <br>
                Pour plus d'information contacter le développeur
            </p>
        </div>
        <?php
    }
    if(!empty($_GET["error"]) && $_GET["error"] == "all"){
        ?>
        <div class="container alert alert-warning mt-3">
            <p>
                Tous les champs doivent être remplis
            </p>
        </div>
        <?php
    }
    if(!empty($_GET["error"]) && $_GET["error"] == "email"){
        ?>
        <div class="container alert alert-warning mt-3">
            <p>
                Cet email est déjà afilié à un autre compte
            </p>
        </div>
        <?php
    }
    if(!empty($_GET["error"]) && $_GET["error"] == "mdp"){
        ?>
        <div class="container alert alert-warning mt-3">
            <p>
                Les mots-de-passe ne sont pas identiques
            </p>
        </div>
        <?php
    }
    if(!empty($_GET["success"]) && $_GET["success"] == "info"){
        ?>
        <div class="container alert alert-success mt-3">
            <p>
                Les informations ont bien été modifiées
            </p>
        </div>
        <?php
    }
    if(!empty($_GET["success"]) && $_GET["success"] == "email"){
        ?>
        <div class="container alert alert-success mt-3">
            <p>
                L'email a bien été modifié
            </p>
        </div>
        <?php
    }
    if(!empty($_GET["success"]) && $_GET["success"] == "mdp"){
        ?>
        <div class="container alert alert-success mt-3">
            <p>
                Le mot-de-passe a bien été modifié
            </p>
        </div>
        <?php
    }
}

function timestampAddDay($nbJours = 1){
    $plusUnJour = new DateTime();
    $plusUnJour->add(new DateInterval('P'. $nbJours .'D'));
    return $plusUnJour->getTimestamp();
}

function dateToFr($date){
    $date = new DateTime($date);
    return $date->format("d/m/Y");
}

function Translate($chaine, $langFrom, $langTo){
	$chaine = urlencode($chaine);
	$url = 'http://translate.google.com/translate_a/t?client=p&text='.$chaine.'&hl='.$langFrom.'&sl='.$langFrom.'&tl='.$langTo.'&ie=UTF-8&oe=UTF-8&multires=1&otf=1&pc=1&trs=1&ssel=3&tsel=6&sc=1';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $retour = explode('"', $response);
    curl_close($ch);
    
	return $retour[1];
}

function resizePicture($file, $w, $h, $crop=FALSE){
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    $src = imagecreatefromjpeg($file);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

function createResizedImage(string $imagePath = '', string $newPath = '', int $newWidth = 0, int $newHeight = 0, string $outExt = 'DEFAULT') : ?string
{
    if (!$newPath or !file_exists ($imagePath)) {
        return null;
    }

    $types = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_BMP, IMAGETYPE_WEBP];
    $type = exif_imagetype ($imagePath);

    if (!in_array ($type, $types)) {
        return "test";
    }

    list ($width, $height) = getimagesize ($imagePath);

    $outBool = in_array ($outExt, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']);

    switch ($type) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg ($imagePath);
            if (!$outBool) $outExt = 'jpg';
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng ($imagePath);
            if (!$outBool) $outExt = 'png';
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif ($imagePath);
            if (!$outBool) $outExt = 'gif';
            break;
        case IMAGETYPE_BMP:
            $image = imagecreatefrombmp ($imagePath);
            if (!$outBool) $outExt = 'bmp';
            break;
        case IMAGETYPE_WEBP:
            $image = imagecreatefromwebp ($imagePath);
            if (!$outBool) $outExt = 'webp';
    }

    $newImage = imagecreatetruecolor ($newWidth, $newHeight);

    //TRANSPARENT BACKGROUND
    $color = imagecolorallocatealpha ($newImage, 0, 0, 0, 127); //fill transparent back
    imagefill ($newImage, 0, 0, $color);
    imagesavealpha ($newImage, true);

    //ROUTINE
    imagecopyresampled ($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Rotate image on iOS
    if(function_exists('exif_read_data') && $exif = exif_read_data($imagePath, 'IFD0'))
    {
        if(isset($exif['Orientation']) && isset($exif['Make']) && !empty($exif['Orientation']) && preg_match('/(apple|ios|iphone)/i', $exif['Make'])) {
            switch($exif['Orientation']) {
                case 8:
                    if ($width > $height) $newImage = imagerotate($newImage,90,0);
                    break;
                case 3:
                    $newImage = imagerotate($newImage,180,0);
                    break;
                case 6:
                    $newImage = imagerotate($newImage,-90,0);
                    break;
            }
        }
    }

    switch (true) {
        case in_array ($outExt, ['jpg', 'jpeg']): $success = imagejpeg ($newImage, $newPath);
            break;
        case $outExt === 'png': $success = imagepng ($newImage, $newPath);
            break;
        case $outExt === 'gif': $success = imagegif ($newImage, $newPath);
            break;
        case  $outExt === 'bmp': $success = imagebmp ($newImage, $newPath);
            break;
        case  $outExt === 'webp': $success = imagewebp ($newImage, $newPath);
    }

    if (!$success) {
        return null;
    }

    return $newPath;
}

// $path = "../assets/src/uuid/";
// $allfolder = scandir("../assets/src/uuid/");
// for ($i = 2; $i < count($allfolder); $i++){
//     $path = "../assets/src/uuid/".$allfolder[$i];
//     $folder = scandir("../assets/src/uuid/".$allfolder[$i]);
//     // print_r($folder);
//     for ($x = 2; $x < count($folder); $x++){
//         // echo $path.$folder[$x];
//         echo createResizedImage($path."/".$folder[$x], $path."/".$folder[$x], 250, 200);
//     }
// }

function pre($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}
function br(){
    echo "<br>";
}