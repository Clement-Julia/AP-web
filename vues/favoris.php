<?php
require_once "header.php";
if(!empty($_SESSION['idUtilisateur'])){
    $Favoris = new Favoris();
    $allFavoris = $Favoris->getAllFavorisForUser($_SESSION['idUtilisateur']);
    ?>
    <div id="main-favoris-container">
        <div id="favoris-container" class="my-3">

    <?php
    if(count($allFavoris) > 0){

        foreach($allFavoris as $favoris){
            $Hebergement = new Hebergement($favoris['idHebergement']);
            $Images = new Images($Hebergement->getUuid());
            $banniere = $Images->getBanniere();
        ?>
    
            <div data-idhebergement="<?=$Hebergement->getIdHebergement()?>" class="card mb-3">
                <div class="row g-0 d-flex align-items-center">
                    <div class="col-md-3">
                        <img src="<?=$banniere?>" class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title"><?=$Hebergement->getLibelle()?></h5>
                            <p class="card-text"><?=$Hebergement->getDescription()?></p>
                            <div class="text-muted"><?=$Hebergement->getVilleLibelle($Hebergement->getIdVille())?></div>
                        </div>
                    </div>
                    <div class="col-md-1 heart-container my-2">
                        <i id="<?=$Hebergement->getIdHebergement()?>" class='fas fa-heart fa-2x' title="Retirer des favoris"></i>
                    </div>
                </div>
            </div>
    
            <?php
            }
            ?>
    
        </div>
    
        <script src="../assets/js/favoris.js"></script>
    
        <?php
    } else { ?>
        <div class="alert alert-warning">Il semblerait que vous n'ayez pas encore ajouté d'hôtel favoris...</div>
    <?php } 

} else { ?>
    <div class="alert alert-warning">Vous devez être connecté pour accéder à ce contenu</div>
<?php } ?>

</div>

<?php
require_once "footer.php";
?>