<?php
require_once "header.php";
if(!empty($_SESSION["try"]) && $_SESSION["try"]["essai"] != 3){
    $now = new DateTime('NOW');
    if($now->diff($_SESSION["try"]["last-try"])->i >= 10){
        $_SESSION["try"]["essai"]++;
    }
};

if(!empty($_GET["erreur"]) && $_GET["erreur"] == "login"){
    ?>
    <div class="container alert alert-warning mt-2">
        L'email ou le mot de passe est incorrect
    </div>
    <?php
}
if(!empty($_GET["erreur"]) && $_GET["erreur"] == "exceed"){
    ?>
    <div class="container alert alert-warning mt-2">
        Nombre de tentative dépassée<br>
        Réessayez dans 10 minutes
    </div>
    <?php
}
?>

<div class="container mt-3">
    <h1>Formulaire de connexion</h1>
    <form method="POST" action="../controleurs/connexion.php" class="needs-validation" novalidate>

        <div class="form-group my-4">
            <label for="email">Email : </label>
            <input type="email" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "login")) ? "is-invalid" : ""?>" name="email" id="email" placeholder="Entrez votre email">

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Email invalide</div>
        </div>

        <div class="form-group my-4">
            <label for="mdp">Mot de passe : </label>
            <input type="password" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "login")) ? "is-invalid" : ""?>" name="mdp" id="mdp" placeholder="Entrez votre mot de passe">

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Mot-de-passe invalide</div>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" name="connection_cookies" id="stay_connected">
            <label class="form-check-label" for="stay_connected">
                Rester connecter
            </label>
        </div>

        <div class="form-group mt-3">
            <div class="g-recaptcha <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all")) ? "is-invalid" : ""?>" data-sitekey="6Lc_V1weAAAAAN5-pK8oskgtOlTJQ5BtWgICPOSh"></div>
            <div class="invalid-feedback">Validation CAPTCHA obligatoire</div>
        </div>
        

        <div class="form-group text-center mt-3">
            <button type="submit" class="btn btn-primary return" name="submit" value="ON">Connexion</button>
            <a href="mail.php" class="btn btn-secondary return">Mot-de-passe oublié</a>
            <a href="index.php" class="btn btn-warning return">Retour</a>
        </div>
    </form>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    (function () {
    'use strict'

    var forms = document.querySelectorAll('.needs-validation');

    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            form.classList.add('was-validated')
        }, false)
        })
    })()

</script>

<?php
require_once "footer.php";
?>