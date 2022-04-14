<?php
require_once "headerAdmin.php";

$admin = new Admin();
$hap = $admin->getHebergementByAskPicture();

?><div class="container"><?php
if(!empty($_GET["success"]) && $_GET["success"] == "ref"){?>
    <div class="alert alert-success">La demande a bien été refusée !</div>
<?php }
elseif(!empty($_GET["success"]) && $_GET["success"] == "acc"){?>
    <div class="alert alert-success">La demande a bien été acceptée !</div>
<?php }
elseif(isset($_GET["error"])){?>
    <div class="alert alert-warning">Un problème est survenu lors de l'execution de la requête demandée.<br>Réessayez plus tard ou contactez un développeur si le problème persiste.</div>
<?php }
?></div>

<div class="container">
    <?php if(!empty($hap) && count($hap) > 0){ ?>
        <h1 class="mb-5">Demande d'ajout de photo :</h1>
        <div class="row table-responsive">
            <table id="Datatable-picture" class="table table-hover table-striped mt-3 align-td display nowrap" width="100%">
                <thead class="bg-primary text-light">
                    <tr>
                    <th scope="col" class="desktop">Propriétaire</th>
                    <th scope="col" class="all">Hébergement</th>
                    <th scope="col" class="desktop">Ville</th>
                    <th scope="col" class="desktop">Région</th>
                    <th scope="col" class="desktop" style ="min-width: 175px !important;">Date de création</th>
                    <th scope="col" class="action desktop text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($hap as $demande){
                            $_SESSION["demande".$demande["idHebergement"]] = $demande;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($demande["nom"], ENT_QUOTES) . " " . htmlspecialchars($demande["prenom"], ENT_QUOTES)?></td>
                                <td><?= htmlspecialchars($demande["libelle"], ENT_QUOTES)?></td>
                                <td><?= htmlspecialchars($demande["libelleVille"], ENT_QUOTES)?></td>
                                <td><?= htmlspecialchars($demande["libelleRegion"], ENT_QUOTES)?></td>
                                <td><?=$demande["dateEnregistrement"]?></td>
                                <td class="btn-group d-none d-md-flex" >
                                    <a href="../controleurs/demandePicture.php?id=<?=$demande["idHebergement"]?>&command=acc" class="btn btn-success">Accepter</a>
                                    <a href="validPictureView.php?id=<?=$demande["idHebergement"]?>" class="btn btn-warning">Gérer</a>
                                    <a href="../controleurs/demandePicture.php?id=<?=$demande["idHebergement"]?>&command=ref" class="btn btn-danger">Refuser</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    
        <script src="vendor/jquery/jquery.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#Datatable-picture').DataTable({
                    language: {
                        url: 'vendor/datatables/FR.json'
                    },
                    responsive : true
                });
            } );
        </script>
    <?php }else{ ?>
        <h3 class="text-muted">Aucune demande d'ajout de photo est en attente</h3>
    <?php } ?>
    <a href="index.php" class="btn btn-secondary mt-5"><i class="fas fa-arrow-left"></i></a>
</div>

<?php
require_once "footerAdmin.php";