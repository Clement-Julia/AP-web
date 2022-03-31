<?php
require_once "headerAdmin.php";

$limit = 10;
$ReservationVoyage = new ReservationVoyage();
$infos = $ReservationVoyage->getAllReservationVoyage();
?>

<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Voyages</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="Datatable-travel" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Voyage</th>
                            <th>Utilisateur</th>
                            <th>Région</th>
                            <th>Prix</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($infos as $info){
                            $date = $ReservationVoyage->getDateVoyage($info["idReservationVoyage"]);
                            ?>
                            <tr>
                                <?=!empty($_GET["id"]) && $_GET["id"] == $info["idCategorie"] ? "<form method='post' action='../traitements/listeCategories.php'>" : "";?>
                                    <td>
                                     Du <span><?= dateToFr($date["dateDebut"]) ?> au <?= dateToFr($date["dateFin"]) ?></span>
                                    </td>
                                    <td>
                                        <span><?=$info["prenom"]." ".$info["nom"];?></span>
                                    </td>
                                    <td>
                                        <span><?=$info["libelle"];?></span>
                                    </td>
                                    <td>
                                        <span><?=$info["prix"];?> €</span>
                                    </td>
                                    <td class="d-flex justify-content-center">
                                        <span>
                                            <a href="vueVoyage.php?id=<?= $info["idReservationVoyage"] ?>" class="text-light btn btn-warning">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="../controleurs/supVoyage.php" class="text-light btn btn-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </span>
                                    </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#Datatable-travel').DataTable({
            language: {
                url: 'vendor/datatables/FR.json'
            }
        });
    } );
</script>

<?php
require_once "footerAdmin.php";