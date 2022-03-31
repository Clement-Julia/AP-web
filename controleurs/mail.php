<?php
require_once "../controleurs/traitement.php";
$users = new Utilisateur();

if($_GET["status"] == "wait" && !empty($_POST["email"])){
    $exist = $users->emailExiste($_POST["email"]);
    
    if($exist){
        $user = $users->getUserByEmail($_POST["email"]);
        $_SESSION["code"] = random_int(10000, 99999);

        $to  = $_POST["email"];
        $subject = 'Votre code de validation pour la récupération de votre identifiant';
        
        $message = file_get_contents("../mail/template_reset.php");
        $message = str_replace("{name}", $user["nom"], $message);
        $message = str_replace("{fistname}", $user["prenom"], $message);
        $message = str_replace("{code}", $_SESSION["code"], $message);
        $message = str_replace("{lien}", 'https://loocalacool.ipssi-sio.fr/vues/index.php', $message);

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';

        mail($to, $subject, $message, implode("\r\n", $headers));
        header("location:../vues/mail.php?wait");
    }else{
        $to  = $_POST["email"];
        $subject = "Demande d'inscription";

        $message = file_get_contents("../mail/template_new.php");
        $message = str_replace("{lien}", 'https://loocalacool.ipssi-sio.fr/vues/index.php', $message);

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';

        mail($to, $subject, $message, implode("\r\n", $headers));
        header("location:../vues/mail.php?success=email");
    }
}else if($_GET["status"] == "code"){
    $code = null;
    for($i = 0; $i < count($_POST["code"]); $i++){
        $code .= $_POST["code"][$i];
    }
    if($code){
        if($code == $_SESSION["code"]){
            $_SESSION["code"] = true;
            header("location:../vues/mail.php?reset");
        }else{
            header("location:../vues/mail.php?wait&erreur=code");
        }
    }else {
        header("location:../vues/mail.php?wait&erreur=type");
    }
}elseif($_GET["status"] == "reset"){
    if($_POST["new_mdp"] == $_POST["new_verif"]){
        $admin->updateUser_mdp($_POST["new_mdp"], $_SESSION["idUtilisateur"]);
        $_SESSION["try"] = ["essai" => 3, "last-try" => $now];
        header("location:../vues/connexion.php?success=mdp");
    }else{
        header("location:../vues/mail.php?reset&error=mdp");
    }
}else{
    header("location:../vues/mail.php?erreur=all");
}