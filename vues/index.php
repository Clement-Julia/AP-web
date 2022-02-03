<?php
require_once "header.php";
?>

<style>
    #navbar{
        background-color: rgba(105, 75, 27, 0.938)!important;
    }

    #navbar a.headerli:hover {
        background-color: rgba(105, 75, 27, 0.726);
    }
</style>

<div id="index-container">

    <div>
        <button type="button" class="offset" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <span id="offset-span">Créez votre voyage</span>    
        </button>
    </div>
    
    <?php
    if(!empty($_SESSION["idUtilisateur"])){
        ?>
        <div class="modal fade " id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form action="../controleurs/startTravelTime.php" method="POST">
                <div class="modal-dialog modal-dialog-centered">
                    <div id="modal-connecter" class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Quelle est la date de votre départ ?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input id="start-date" name="date" type="date" require>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="close" data-bs-dismiss="modal">Retour</button>
                            <button id="submit" type="submit" class="raise">Valider</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php
    }else{
        ?>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div id="modal-non-connecter" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Connection obligatoire</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <br><br><br>
                        <!-- <p>Vous devez être connecter pour pouvoir créer et plannifier votre voyage !</p> -->
                    </div>
                    <div class="modal-footer">
                    <a href="inscription.php"><button type="button" class="up">S'inscrire</button>
                    <a href="connexion.php"><button type="button" class="up2">Se connecter</button></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
    
</div>

<script src="../assets/js/index.js"></script>

<?php
require_once "footer.php";
?>