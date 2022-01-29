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
            <table class="table table-hover table-striped mt-3 align-td">
                <thead class="bg-primary text-light">
                    <tr>
                    <th scope="col">Propriétaire</th>
                    <th scope="col">Hébergement</th>
                    <th scope="col">Ville</th>
                    <th scope="col">Région</th>
                    <th scope="col" style ="min-width: 175px !important;">Date de création</th>
                    <th scope="col" class="action text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($hap as $demande){
                            $_SESSION["demande".$demande["idHebergement"]] = $demande;
                            ?>
                            <tr>
                                <td><?=$demande["nom"] . " " . $demande["prenom"]?></td>
                                <td><?=$demande["libelle"]?></td>
                                <td><?=$demande["libelleVille"]?></td>
                                <td><?=$demande["libelleRegion"]?></td>
                                <td><?=$demande["dateEnregistrement"]?></td>
                                <td class="btn-group d-flex" >
                                    <a href="../controleurs/demandePicture.php?id=<?=$demande["idHebergement"]?>&command=acc" class="btn btn-success">Accepter</a>
                                    <a href="validPictureView.php?id=<?=$demande["idHebergement"]?>" class="btn btn-warning">Gérer</a>
                                    <a href="v../controleurs/demandePicture.php?id=<?=$demande["idHebergement"]?>&command=ref" class="btn btn-danger">Refuser</a>
                                </td>
                            </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php }else{ ?>
        <h3 class="text-muted">Aucune demande d'ajout de photo est en attente</h3>
    <?php } ?>
    <a href="index.php" class="btn btn-secondary mt-5"><i class="fas fa-arrow-left"></i></a>
</div>

<?php
require_once "footerAdmin.php";