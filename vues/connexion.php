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
    html,body{
        background-image: url('../assets/src/img/background/connexion.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        height: 100%;
        font-family: 'Numans', sans-serif;
    }
    
    .card{
        height: 370px;
        margin-top: auto;
        margin-bottom: auto;
        width: 400px;
        background-color: rgba(0,0,0,0.5) !important;
    }

    .social_icon span{
        font-size: 60px;
        margin-left: 10px;
        color: #FFC312;
    }
        
    .social_icon span:hover{
        color: white;
        cursor: pointer;
    }
        
    .card-header h3{
        color: white;
    }
        
    .social_icon{
        position: absolute;
        right: 20px;
        top: -45px;
    }

    input:focus{
        outline: 0 0 0 0  !important;
        box-shadow: 0 0 0 0 !important;
    }
        
    .remember{
        color: white;
    }
        
    .remember input{
        width: 20px;
        height: 20px;
        margin-left: 15px;
        margin-right: 5px;
    }
        
    .login_btn{
        color: black;
        background-color: #FFC312;
        width: 100px;
    }
        
    .login_btn:hover{
        color: black;
        background-color: white;
    }
        
    .links{
        color: white;
    }
        
    .links a{
        margin-left: 4px;
    }

    .label-input{
        width: 50px;
        background-color: rgb(255 195 18);
        color: rgb(0 0 0);
        border: 0 !important;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }

    #connexion, #inscription{
        color: white !important;
    }
</style>

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