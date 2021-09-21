<?php
require_once "headerAdmin.php";
?>

<div class="container">
    <h1>Création d'un utilisateur :</h1>
    <form method="POST" action="../controleurs/addUser.php">
    
        <div class="form-group my-3">
            <label for="email" class="mb-1">Email : </label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Entrez votre email" required>
        </div>

        <div class="form-group my-3">
            <label for="nom" class="mb-1">Nom : </label>
            <input type="text" class="form-control" name="nom" id="nom" placeholder="Entrez votre nom" required>
        </div>

        <div class="form-group my-3">
            <label for="prenom" class="mb-1">Prénom : </label>
            <input type="text" class="form-control" name="prenom" id="prenom" placeholder="Entrez votre prénom" required>
        </div>

        <div class="form-group my-3">
            <label for="mdp" class="mb-1">Mot de passe : </label>
            <input type="password" class="form-control" name="mdp" id="mpd" placeholder="Entrez votre mot de passe" required>
        </div>

        <div class="form-group my-3">
            <label for="mdpVerif" class="mb-1">Vérification du mot de passe : </label>
            <input type="password" class="form-control" name="mdpVerif" id="mdpVerif" placeholder="Vérifier votre mot de passe" required>
        </div>

        <div class="form-group my-3">
            <label for="age" class="mb-1">Âge : </label>
            <input type="number" class="form-control" name="age" id="age" min=0 placeholder="Entrez votre âge" required>
        </div>

        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary">Créer</button>
        </div>

    </form>
</div>
<?php
    

require_once "footerAdmin.php";