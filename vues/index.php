<?php
require_once "header.php";
 // ------------------ a supprimer car entré en dur pour phase de test -------------------------------
 $_SESSION['idUtilisateur'] = 1;
?>

<div id="index-container">

    <div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Créez votre voyage
        </button>
    </div>
    



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
</div>


<script src="../js/index.js"></script>

<?php
require_once "footer.php";
?>