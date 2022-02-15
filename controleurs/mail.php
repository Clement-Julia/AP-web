<?php
require_once "../controleurs/traitement.php";
$users = new Utilisateur();

if(!empty($_POST["email"]) && empty($_POST["code"])){
    $exist = $users->emailExiste($_POST["email"]);
    
    if($exist){
        $user = $users->getUserByEmail($_POST["email"]);
        $_SESSION["code"] = random_int(10000, 99999);

        $to  = $_POST["email"];
        $subject = 'Votre code de validation pour la récupération de votre identifiant';

        // $data = array ('name' => $user["nom"], 'firstname' => $user["prenom"], 'lien' => 'https://loocalacool.ipssi-sio.fr/vues/index.php', 'code' => $code);
        // $data = http_build_query($data);
        // $opts = array(
        //     'http'=>array(
        //         'method'=>"POST",
        //         'content' => $data
        //     )
        // );
        // $options = stream_context_create($opts);
        // $message = file_get_contents("../mail/template_reset.php", false, $options);
        
        $message = file_get_contents("../mail/template_reset.php");
        $message = str_replace("{name}", $user["nom"], $message);
        $message = str_replace("{fistname}", $user["prenom"], $message);
        $message = str_replace("{code}", $_SESSION["code"], $message);
        $message = str_replace("{lien}", 'https://loocalacool.ipssi-sio.fr/vues/index.php', $message);

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // mail($to, $subject, $message, implode("\r\n", $headers));
        header("location:../vues/mail.php?wait");
    }else{
        $to  = $_POST["email"];
        $subject = "Demande d'inscription";

        $message = file_get_contents("../mail/template_new.php");
        $message = str_replace("{lien}", 'https://loocalacool.ipssi-sio.fr/vues/index.php', $message);

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        // mail($to, $subject, $message, implode("\r\n", $headers));
        header("location:../vues/mail.php?success=email");
    }
}else {
    header("location:../vues/mail.php?erreur=all");
}

if(empty($_POST["email"]) && !empty($_POST["code"])){
    if(is_int($_POST["code"])){
        if($_POST["code"] == $_SESSION["code"]){
            $_SESSION["code"] = true;
            header("location:../vues/mail.php?reset");
        }else{
            header("location:../vues/mail.php?wait&erreur=code");
        }
    }else {
        header("location:../vues/mail.php?wait&erreur=type");
    }
}else {
    header("location:../vues/mail.php?wait&erreur=all");
}

if(!empty($_POST["new_mdp"]) && !empty($_POST["new_verif"])){
    if($_POST["new_mdp"] == $_POST["new_verif"]){
        $admin->updateUser_mdp($_POST["new_mdp"], $_SESSION["idUtilisateur"]);
        header("location:../vues/connexion.php?success=mdp");
    }else{
        header("location:../vues/mail.php?reset&error=mdp");
    }
}else{
    header("location:../vues/mail.php?reset&erreur=all");
}