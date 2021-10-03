<?php

function sortFunction( $a, $b ) {
    return strtotime($a) - strtotime($b);
}

function isValidDate($date, $format = 'Y-m-d'){
    $dt = DateTime::createFromFormat($format, $date);
    return $dt && $dt->format($format) === $date;
}

function lister_images($repertoire){  
    if(is_dir($repertoire)){  
        if($iteration = opendir($repertoire)){  
            while(($fichier = readdir($iteration)) !== false){  
                if($fichier != "." && $fichier != ".."){ 
                    $fichier_info = finfo_open(FILEINFO_MIME_TYPE);
                    $mime_type = finfo_file($fichier_info, $repertoire.$fichier);
                    if(strpos($mime_type, 'image/') === 0){
                        echo '<img src="'.$repertoire.$fichier.'" class="img-fluid rounded float-start badgetest" style="max-width: 300px">';
                    }
                }  
            }  
            closedir($iteration);  
        }  
    }  
}

function test(){
    echo "Test";
}