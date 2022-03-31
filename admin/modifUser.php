<?php
require_once "headerAdmin.php";
$admin = new Admin();
$users = $admin->getAllUsers();

$date = new DateTime();
?>

<?php
    if(!empty($_GET["error"]) && $_GET["error"] == "crash"){
        ?>
        <div class="container alert alert-danger">
            <p>
                La fonctionnalité est actuellement indisponible <br>
                Pour plus d'information contacter le développeur
            </p>
        </div>
        <?php
    }
    if(isset($_GET["success"])){
        ?>
        <div class="container alert alert-success">
            <p>
                L'utilisateur a bien été banni !
            </p>
        </div>
        <?php
    }
?>

<?php
if(empty($_GET["id"])){
    ?>
    <div class="container mb-4">
        <h1>Liste des utilisateurs :</h1>
        <table id="Datatable-user" class="table table-hover mt-3 align-td" width="100%" cellspacing="0">
            <thead class="bg-primary text-light">
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Email</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($users as $user){
                        ?>
                        <tr>
                            <td><?=$user["idUtilisateur"]?></td>
                            <td><?=htmlspecialchars($user["email"], ENT_QUOTES)?></td>
                            <td><?=htmlspecialchars($user["nom"], ENT_QUOTES)?></td>
                            <td><?=htmlspecialchars($user["prenom"], ENT_QUOTES)?></td>
                            <td class="d-flex justify-content-center">
                                <span>
                                    <a href="../controleurs/ban.php?id=<?=$user["idUtilisateur"]?>&ban=user" class="btn btn-danger" title="Bannir">
                                        <i class="fas fa-gavel"></i>
                                    </a>
                                    <a href="modifUser.php?id=<?=$user["idUtilisateur"]?>" class="text-light btn btn-warning" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="text-light btn btn-danger" data-toggle="modal" data-target="#modalDelete<?=$user["idUtilisateur"]?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </span>
                            </td>
                        </tr>

                        <div id="modalDelete<?=$user["idUtilisateur"]?>" class="modal fade">
                            <div class="modal-dialog modal-confirm modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header flex-column">
                                        <div class="icon-box">
                                            <i class="material-icons"><i class="fas fa-times"></i></i>
                                        </div>
                                        <h4 class="modal-title w-100">Êtes-vous sûr ?</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Vous êtes sur le point de supprimer le compte de <?=$user["nom"]." ".$user["prenom"]?></p>
                                    </div>
                                    <div class="modal-footer justify-content-center">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Retour</button>
                                        <form action="../controleurs/supUser.php" method="GET">
                                            <input type="hidden" name="id" value="<?=$user["idUtilisateur"]?>">
                                            <button type="submit" class="btn btn-danger">Supprimer</button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                ?>
            </tbody>
        </table>
    </div>
    
    <script src="vendor/jquery/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#Datatable-user').DataTable({
                language: {
                    url: 'vendor/datatables/FR.json'
                }
            });
        } );
    </script>
    <?php
}else{
    $user = new Utilisateur($_GET["id"]);
    $_SESSION["supUser"] = $user->getIdUtilisateur();
    ?>
    <div class="container">
        <h1>Modification d'un utilisateur :</h1>
        <form method="POST" action="../controleurs/modifUser.php?id=<?=$user->getIdUtilisateur()?>">
        
            <div class="form-group my-3">
                <label for="email" class="mb-1">Email : </label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Entrez votre email" value="<?=$user->getEmail()?>" required>
            </div>

            <div class="form-group my-3">
                <label for="nom" class="mb-1">Nom : </label>
                <input type="text" class="form-control" name="nom" id="nom" placeholder="Entrez votre nom" value="<?=htmlspecialchars($user->getNom(), ENT_QUOTES)?>" required>
            </div>

            <div class="form-group my-3">
                <label for="prenom" class="mb-1">Prénom : </label>
                <input type="text" class="form-control" name="prenom" id="prenom" placeholder="Entrez votre prénom" value="<?=htmlspecialchars($user->getPrenom(), ENT_QUOTES)?>" required>
            </div>

            <div class="form-group my-3">
                <label for="age" class="mb-1">Date de naissance : </label>
                <input type="date" class="form-control" name="age" id="age" min=0 placeholder="Date de naissance" max="<?= $date->format('Y-m-d') ?>" value="<?= $user->getBirth() ?>" required>
            </div>

            <div class="form-group text-center mt-4">
                <button type="submit" class="btn btn-warning">Modifier</button>

                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">
                    Supprimer
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                Vous êtes sur le point de supprimer le compte de <?=$user->getNom()." ".$user->getPrenom()?>
                                <br> Êtes-vous sûr ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Non</button>
                                <a href="../controleurs/supUser.php"><button type="button" class="btn btn-danger">Oui</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>
        <a href="modifUser.php" class="btn btn-secondary mt-5"><i class="fas fa-arrow-left"></i></a>
    </div>
    <?php
}
?>

<?php
require_once "footerAdmin.php";
?>