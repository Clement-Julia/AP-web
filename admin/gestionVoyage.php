<?php
require_once "headerAdmin.php";

$limit = 10;
$voyages = new ReservationVoyage();
$count = $voyages->getCountReservationVoyage();
$nbPages = round($count["count"] / $limit);
$infos = $voyages->getAllReservationVoyage();
?>

<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Voyages</h6>
            <nav>
                <ul class="pagination">
                    <li class="page-item <?=!isset($_GET["pages"]) || $_GET["pages"] == 0 ? "disabled" : "";?>"><a class="page-link" href="listeCategories.php?pages=<?=isset($_GET["pages"]) ? $_GET["pages"] - $limit : 0;?>">&laquo</a></li>
                    <?php
                    for($i = 1; $i <= $nbPages; $i++){
                        ?>
                        <li class="page-item <?=(!isset($_GET["pages"]) && $i == 1) || isset($_GET["pages"]) && $_GET["pages"] == (($i-1)*$limit) ? "active" : "";?>"><a class="page-link" href="listeCategories.php?pages=<?=($i-1)*$limit;?>"><?=$i;?></a></li>
                        <?php
                    }
                    ?>
                    <li class="page-item <?=(isset($_GET["pages"]) && $_GET["pages"] == (($nbPages * $limit) - $limit)) || $nbPages == 1 ? "disabled" : "";?>"><a class="page-link" href="listeCategories.php?pages=<?=!isset($_GET["pages"]) ? $limit : $_GET["pages"] + $limit;?>">&raquo</a></li>
                </ul>
            </nav>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>IdVoyage</th>
                            <th>IdUSer</th>
                            <th>Prix</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        foreach($infos as $info){
                            ?>
                            <tr>
                                <?=!empty($_GET["id"]) && $_GET["id"] == $info["idCategorie"] ? "<form method='post' action='../traitements/listeCategories.php'>" : "";?>
                                    <td>
                                        <span><?=$info["idReservationVoyage"];?></span>
                                    </td>
                                    <td>
                                        <span><?=$info["idUtilisateur"];?></span>
                                    </td>
                                    <td>
                                        <span><?=$info["prix"];?> €</span>
                                    </td>
                                    <td>
                                        <span>
                                            <a href="vueVoyage.php" class="text-light btn btn-warning">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editVoyage.php" class="text-light btn btn-info">
                                                <i class="fas fa-edit"></i>
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

<?php
require_once "footerAdmin.php";