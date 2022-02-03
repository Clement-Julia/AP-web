<?php
require_once "header.php";
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

<div class="container" style="height:80%">
	<div class="d-flex justify-content-center h-100">
		<div class="card">
			<div class="card-header">
				<h3>Connexion</h3>
			</div>
			<div class="card-body">
				<form method="POST" action="../controleurs/connexion.php" class="needs-validation" novalidate>
					<div class="input-group form-group mb-2">
                        <span class="input-group-text label-input"><i class="fas fa-user"></i></span>
						<input type="email" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "email")) ? "is-invalid" : ""?>" name="email" id="email" placeholder="Identifiant">
					</div>
					<div class="input-group form-group">
                        <span class="input-group-text label-input"><i class="fas fa-key"></i></span>
						<input type="password" class="form-control <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "mdp")) ? "is-invalid" : ""?>" name="mdp" id="mdp" placeholder="Mot de passe">
					</div>
					<div class="row align-items-center remember mt-4">
						<input type="checkbox">Se souvenir de moi
					</div>
					<div class="form-group mt-5">
						<input type="submit" value="Login" class="btn float-right login_btn">
					</div>
				</form>
			</div>
			<div class="card-footer">
				<div class="d-flex justify-content-center links">
					Vous n'avez pas de compte ?<a href="#">Inscription</a>
				</div>
			</div>
		</div>
	</div>
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