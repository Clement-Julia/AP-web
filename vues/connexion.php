<?php
require_once "header.php";
?>

<div class="container mt-3">
    <h1>Formulaire de connexion</h1>
    <form method="POST" action="../controleurs/connexion.php" class="needs-validation" novalidate>

        <div class="form-group my-4">
            <label for="email">Email : </label>
            <input type="email" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "email")) ? "is-invalid" : ""?>" name="email" id="email" placeholder="Entrez votre email">

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Email invalide</div>
        </div>

        <div class="form-group my-4">
            <label for="mdp">Mot de passe : </label>
            <input type="password" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "mdp")) ? "is-invalid" : ""?>" name="mdp" id="mdp" placeholder="Entrez votre mot de passe">

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Mot-de-passe invalide</div>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="" name="connection_cookies" id="stay_connected">
            <label class="form-check-label" for="stay_connected">
                Rester connecter
            </label>

        </div>

        <div class="form-group text-center mt-3">
            <button type="submit" class="btn btn-primary return" name="submit" value="ON">Connexion</button>
            <a href="index.php" class="btn btn-warning return">Retour</a>
        </div>
    </form>
</div>

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