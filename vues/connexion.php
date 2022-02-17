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

<style>

    html{
        height: 100%;
    }

    body{
        background-image: url('../assets/src/img/background/connexion.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        height: 100%;
        font-family: Georgia, serif;
        color: white;
    }

    body:after {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        content: '';
        background: #000;
        opacity: .3;
        z-index: -1;
        height: 100%;
    }

    @media (max-height: 700px) {
        body, body:after {
            background-size: auto;
            height: 700px;
        }
    }

    form{
        min-width: 30%;
    }

    .form-input{
        width: 100%;
        background: rgb(0 0 0 / 0%);
        border: none;
        height: 50px;
        color: rgb(255 255 255) !important;
        border: 1px solid rgb(0 0 0 / 0%);
        background: rgb(255 255 255 / 8%);
        border-radius: 40px;
        padding-left: 20px;
        padding-right: 20px;
        -webkit-transition: 0.3s;
        -o-transition: 0.3s;
        transition: 0.3s;
    }

    .form-input:hover, .form-input:focus {
        background: transparent;
        outline: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        border-color: rgba(255, 255, 255, 0.4);
    }

    .form-input:focus {
        border-color: rgba(255, 255, 255, 0.4);
    }

    .form-input::placeholder {
        color: rgba(255, 255, 255, 0.8) !important;
        opacity: 1;
    }

    .form-label{
        color: rgb(251 206 181);
    }

    .form-checkbox input:checked ~ .form-checkbox:after{
        color: #7851a9;
    }

    .checkbox-wrap {
        display: block;
        position: relative;
        padding-left: 30px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .checkbox-wrap input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
    }

    .checkmark:after {
        content: "\f0c8";
        font-family: "FontAwesome";
        position: absolute;
        color: #7851a9;
        font-size: 20px;
        margin-top: -4px;
        -webkit-transition: 0.3s;
        -o-transition: 0.3s;
        transition: 0.3s; }
        @media (prefers-reduced-motion: reduce) {
            .checkmark:after {
            -webkit-transition: none;
            -o-transition: none;
            transition: none;
        }
    }

    .checkbox-wrap input:checked ~ .checkmark:after {
        display: block;
        content: "\f14a";
        font-family: "FontAwesome";
        color: rgba(0, 0, 0, 0.2);
    }

    .form-checkbox {
        color: #c7c0c0
    }

    .form-checkbox input:checked ~ .checkmark:after {
        color: #c7c0c0
    }

    .form-btn{
        background: rgb(255 255 255 / 15%);
        /* color: rgb(192 188 188); */
        color: white;
        backdrop-filter: blur(12px);
        border-radius: 40px;
        font-size: 15px;
    }

    .form-btn:hover{
        background: rgb(255 255 255 / 20%);
        backdrop-filter: blur(15px);
        border-color: rgb(255 255 255 / 40%);
        color: white;
    }

    .invalid-feedback {
        color: rgb(245 73 89);
    }

    .valid-feedback {
        color: rgb(52 235 150);
    }

    .container{
        backdrop-filter: blur(1px);
    }
</style>

<div class="container mt-5 d-flex flex-column align-items-center">
    <h1>Connexion</h1>
    <form method="POST" action="../controleurs/connexion.php" class="needs-validation" novalidate>

        <div class="form-group my-4">
            <input type="email" class="form-input <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "login")) ? "is-invalid" : ""?>" name="email" id="email" placeholder="Email">

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Email invalide</div>
        </div>

        <div class="form-group my-4">
            <input type="password" class="form-input <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "login")) ? "is-invalid" : ""?>" name="mdp" id="mdp" placeholder="Mot-de-passe">

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Mot-de-passe invalide</div>
        </div>

        <div class="form-group d-flex flex-column align-items-center text-center">
            <div class="g-recaptcha <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all")) ? "is-invalid" : ""?>" data-sitekey="6Lc_V1weAAAAAN5-pK8oskgtOlTJQ5BtWgICPOSh" data-theme="dark"></div>
            <div class="invalid-feedback">Validation CAPTCHA obligatoire</div>
        </div>

        <div class="form-group mt-3 d-flex flex-column align-items-center">
            <label class="checkbox-wrap form-checkbox <?=(!empty($_GET["erreur"]) && $_GET["erreur"] == "all") ? "is-invalid" : ""?>">Rester connecter
                <input type="checkbox" id="stay_connected" name="connection_cookies" class="<?=(!empty($_GET["erreur"]) && $_GET["erreur"] == "all") ? "is-invalid" : ""?>" checked required>
                <span class="checkmark"></span>
            </label>
        </div>

        <div class="form-group text-center mt-4">
            <button type="submit" class="btn form-btn return" name="submit" value="ON">Connexion</button>
            <a href="mail.php" class="btn form-btn return">Mot-de-passe oublié</a>
            <a href="index.php" class="btn form-btn return">Retour</a>
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