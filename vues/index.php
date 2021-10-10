<?php
require_once "header.php";

?>

<div id="index-container">

    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Créez votre voyage
        </button>
        <div id="index-form" class="card d-none">
            <form action="../controleurs/startTravelTime.php" method="POST">
                <div class="card-header text-center"><h6>Choississez votre départ</h6></div>
                <div class="card-body d-flex">
                    <div id="item-input">
                        <input id="start-date" name="date" type="date" require>
                    </div>
                    <div id="item-button-container p-1">
                        <button id="submit" type="submit" class="btn btn-outline-success btn-sm m-1">Start</button>
                        <button type="button" class="btn btn-outline-secondary btn-sm my-1" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <?php
    if(!empty($_SESSION)){
        ?>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <form action="../controleurs/startTravelTime.php" method="POST">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Quelle est la date de votre départ ?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input id="start-date" name="date" type="date" require>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button id="submit" type="submit" class="btn btn-success">Start</button>
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
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Attention !</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Vous devez être connecter pour pouvoir créer et plannifier votre voyage !</p>
                    </div>
                    <div class="modal-footer">
                    <a href="inscription.php"><button type="button" class="btn btn-primary">S'inscrire</button>
                    <a href="connexion.php"><button type="button" class="btn btn-success">Se connecter</button></a>
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