<?php

function sortFunction( $a, $b ) {
    return strtotime($a) - strtotime($b);
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
                    $mime_type = finfo_file($fichier_info, $repertoire.$fichier);
                    if(strpos($mime_type, 'image/') === 0){
                        echo
                        '<img src="'.$repertoire.$fichier.'"id="img'.$i.'" name="'.$repertoire.$fichier.'" class="img-fluid rounded float-start badgetest" style="max-width: 300px">' . 
                        '<button type="button" id="btn'.$i.'" style="visibility: hidden" onclick="supImage()">
                            <span class="badge badge-danger rounded position-badge" style="visibility: visible"><i class="fas fa-times fa-lg" aria-hidden=true></i></span>
                        </button>';
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