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
                            echo
                            '<img src="'.$repertoire."/".$fichier.'"id="img'.$i.'" name="'.$repertoire."/".$fichier.'" class="img-fluid rounded float-start badgetest" style="max-width: 300px">' . 
                            '<button type="button" id="'.$i.'" style="visibility: hidden" onclick="supImage(this.id)">
                                <span class="badge badge-danger rounded position-badge" style="visibility: visible"><i class="fas fa-times fa-lg" aria-hidden=true></i></span>
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

function error($type){
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

function Translate($chaine, $langFrom, $langTo)
{
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