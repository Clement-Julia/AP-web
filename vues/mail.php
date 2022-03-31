<?php
require_once "header.php";
?>

<?php
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

if(isset($_GET["wait"])){
    ?>
        <div class="container mt-5 d-flex flex-column align-items-center">
            <form method="POST" action="../controleurs/mail.php?status=code" class="needs-validation" novalidate>
                <h4 class="mt-5">Code reçu :</h4>
                <div class="form-group my-4 d-flex">
                    <input type="number" class="form-input" name="code[]" min="0" max ="9" onkeypress="return event.charCode >= 48 && event.charCode <= 57 && this.value == ''">
                    <input type="number" class="form-input" name="code[]" min="0" max ="9">
                    <input type="number" class="form-input" name="code[]" min="0" max ="9">
                    <input type="number" class="form-input" name="code[]" min="0" max ="9">
                    <input type="number" class="form-input" name="code[]" min="0" max ="9">

                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Code invalide</div>
                </div>

                <div class="form-group text-center mt-5">
                    <button type="submit" class="btn form-btn return" name="submit" value="ON">Envoyé</button>
                    <a href="connexion.php" class="btn form-btn return">Retour</a>
                </div>
            </form>
        </div>
    <?php
}elseif(isset($_GET["reset"]) && $_SESSION["code"]){
    ?>
        <div class="container mt-5 d-flex flex-column align-items-center">
            <form method="POST" action="../controleurs/mail.php?status=reset" class="needs-validation" novalidate>
                <h3 class="mt-5">Changement mot-de-passe :</h3>
                <div class="col-md-12 mt-3">
                    <input type="text" name="new_mdp" class="form-input <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all")) ? "is-invalid" : ""?>" placeholder="Nouveau mot-de-passe" value="">
                    
                    <div class="invalid-feedback">Mot de passe invalide</div> 
                </div>
                <div class="col-md-12 mt-3">
                    <input type="text" name="new_verif" class="form-input <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all")) ? "is-invalid" : ""?>" placeholder="Vérification nouveau mot-de-passe" value="">

                    <div class="invalid-feedback">Mot de passe invalide</div> 
                </div>
                <div class="form-group text-center mt-5">
                    <button type="submit" class="btn form-btn return" name="submit" value="ON">Sauvegarder</button>
                    <a href="connexion.php" class="btn form-btn return">Retour</a>
                </div>
            </form>
        </div>

    <?php
}else{
    ?>
        <div class="container mt-5 d-flex flex-column align-items-center">
            <form method="POST" action="../controleurs/mail.php?status=wait" class="needs-validation" novalidate>
                <h3 class="mt-5">Envoyé un mail de récupération à :</h3>
                <div class="form-group my-4">
                    <input type="email" class="form-input <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all")) ? "is-invalid" : ""?>" name="email" id="email" placeholder="Email de récupération">

                    <div class="valid-feedback">Ok !</div>
                    <div class="invalid-feedback">Email invalide</div>
                </div>

                <div class="form-group text-center mt-5">
                    <button type="submit" class="btn form-btn return" name="submit" value="ON">Envoyé</button>
                    <a href="connexion.php" class="btn form-btn return">Retour</a>
                </div>
            </form>
        </div>
    <?php
}

require_once "footer.php";
?>
</div>