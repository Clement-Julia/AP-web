<!DOCTYPE html>
<html lang="fr">
    <body style="background-color: rgb(230 236 239)">

        <div style="background-color: white; width:50%;margin: 0 auto;">
            <div style="text-align:center">
                <img id="logo" src="../assets/src/img/locallacol.png" alt="Logo du site locallacol" style="width:30%;margin-top:20px"><br>
                <p style="color: rgb(212 33 33);text-transform: uppercase;">Demande de réinitialisation de mot de passe</p>
            </div>
            
            <div style="padding:5%; color:#999999">
                <?php echo "test : " . print_r($_POST) ?>
                <b>Bonjour {name} {fistname},</b>
                <p>Le code unique à saisir pour confirmer la réinitialisation de votre mot de passe est le {code} . <br>
 
                Ce code est valable pour une durée de 10 minutes. Au-delà, vous devrez recommencer la procédure.</p>
                
                <p>Vous n'êtes pas à l'origine de cette opération ? Changez votre mot-de-passe par mesure de sécurité.</p>
                
                
                A bientôt sur votre Espace Client,<br>
                
                L'équipe loocalacool,<br>
                <a href="{lien}"><img id="logo" src="../assets/src/img/locallacol.png" alt="Logo du site locallacol" style="width:15%;margin-top:10px"><br></a>
            </div>

        </div>

    </body>
</html>