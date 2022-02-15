<?php
require_once "header.php";

if(!empty($_GET["success"]) && $_GET["success"] == "email"){
    ?>
    <div class="container alert alert-success mt-2">
        L'email a bien été envoyé !
    </div>
    <?php
}
if(!empty($_GET["success"]) && $_GET["success"] == "mdp"){
    ?>
    <div class="container alert alert-success mt-2">
        Le mot de passe a bien été changé !
    </div>
    <?php
}
if(!isset($_GET["wait"])){
    ?>
        <div class="container mt-3">
            <h1>Formulaire de récupération</h1>
            <form method="POST" action="../controleurs/mail.php" class="needs-validation" novalidate>

                <div class="form-group my-4">
                    <label for="email">Envoyé un mail de récupération à : </label>
                    <input type="email" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all")) ? "is-invalid" : ""?>" name="email" id="email" placeholder="Entrez votre email">

                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Email invalide</div>
                </div>

                <div class="form-group text-center mt-3">
                    <button type="submit" class="btn btn-primary return" name="submit" value="ON">Envoyé</button>
                    <a href="connexion.php" class="btn btn-warning return">Retour</a>
                </div>
            </form>
        </div>
    <?php
}elseif(!isset($_GET["reset"]) && $_SESSION["code"]){
    ?>
        <div class="container mt-3">
            <h1>Formulaire de récupération</h1>
            <form method="POST" action="../controleurs/mail.php" class="needs-validation" novalidate>

                <div class="col-md-12 mt-3">
                    <label class="labels">Nouveau mot-de-passe :</label>
                    <input type="text" name="new_mdp" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all")) ? "is-invalid" : ""?>" placeholder="Entrez votre nouveau mot-de-passe" value="">
                    
                    <div class="invalid-feedback">Mot de passe invalide</div> 
                </div>
                <div class="col-md-12 mt-3">
                    <label class="labels">Vérification nouveau mot-de-passe :</label>
                    <input type="text" name="new_verif" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all")) ? "is-invalid" : ""?>" placeholder="Entrez à nouveau votre nouveau mot-de-passe" value="">

                    <div class="invalid-feedback">Mot de passe invalide</div> 
                </div>
                <div class="form-group text-center mt-3">
                    <button type="submit" class="btn btn-primary return" name="submit" value="ON">Sauvegarder</button>
                    <a href="connexion.php" class="btn btn-warning return">Retour</a>
                </div>
            </form>
        </div>

    <?php
}else{
    ?>
        <div class="container mt-3">
            <h1>Formulaire de récupération</h1>
            <form method="POST" action="../controleurs/mail.php" class="needs-validation" novalidate>

                <div class="form-group my-4">
                    <label for="code">Code reçu : </label>
                    <input type="number" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || !empty($_GET["erreur"]) && $_GET["erreur"] == "mdp" || !empty($_GET["erreur"]) && $_GET["erreur"] == "type")) ? "is-invalid" : ""?>" name="code" id="code" min="0" placeholder="Entrer le code">

                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Code invalide</div>
                </div>

                <div class="form-group text-center mt-3">
                    <button type="submit" class="btn btn-primary return" name="submit" value="ON">Envoyé</button>
                    <a href="connexion.php" class="btn btn-warning return">Retour</a>
                </div>
            </form>
        </div>
    <?php
}

require_once "footer.php";
?>