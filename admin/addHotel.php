<?php
require_once "headerAdmin.php";
?>


<div class="container">
    <h1 class="mb-3">Ajout d'un hébergement :</h1>
    <form method="POST" action="../controleurs/addVille.php" enctype="multipart/form-data">

        <div class="form-group">
            <label for="name">Nom : </label>
            <input type="text" class="form-control" name="name" id="name" placeholder="Entrez le nom d'un hébergement" required>
        </div>

        <div class="form-group">
            <label for="description">Description : </label>
            <textarea class="form-control" name="description" id="description" cols="30" rows="10" placeholder="Entrez la description de l'hébergement"></textarea>
        </div>

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-link text-muted" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Via coordonnées</a>
                <a class="nav-link text-muted" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Via lien google map</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="form-group mt-4">
                    <label for="latitude">Latitude : </label>
                    <input type="text" class="form-control" name="latitude" id="latitude" placeholder="Entrez une latitude">
                </div>

                <div class="form-group">
                    <label for="longitude">Longitude : </label>
                    <input type="text" class="form-control" name="longitude" id="longitude" placeholder="Entrez une longitude">
                </div>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="form-group mt-4">
                    <label for="link">Lien google map : </label>
                    <input type="text" class="form-control" name="link" id="link" placeholder="Entrez le lien">
                </div>
            </div>
        </div>
        
        <!-- <div class="form-group">
            <label>Images : </label><br>
            <input type="file" name="image[]" multiple>
        </div> -->

        <div class="form-group text-center mt-4">
            <button type="submit" class="btn btn-primary" name="submit" value="ON">Créer</button>
        </div>

    </form>
</div>


<?php
require_once "footerAdmin.php";