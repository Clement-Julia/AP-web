<?php
require_once "header.php";
?>

<div class="container mt-3">
    <h1>Formulaire de connexion</h1>
    <form method="POST" action="../controleurs/connexion.php">

        <div class="form-group my-4">
            <label for="email">Email : </label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Entrez votre email">
        </div>

        <div class="form-group my-4">
            <label for="mdp">Mot de passe : </label>
            <input type="password" class="form-control" name="mdp" id="mdp" placeholder="Entrez votre mot de passe">
        </div>

        <div class="form-group text-center mt-3">
            <button type="submit" class="btn btn-primary return" name="submit" value="ON">Connexion</button>
            <a href="index.php" class="btn btn-warning return">Retour</a>
        </div>
    </form>
</div>

<?php
require_once "footer.php";
?>