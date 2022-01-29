<?php
require_once "headerAdmin.php";
$admin = new Admin();
$users = $admin->getAllUsers();
?>

<?php
if(empty($_GET)){
    ?>
    <div class="container">
        <h1>Liste des utilisateurs :</h1>
        <table class="table table-hover mt-3 align-td">
        <thead class="bg-primary text-light">
            <tr>
            <th scope="col">Id</th>
            <th scope="col">Email</th>
            <th scope="col">Nom</th>
            <th scope="col">Prénom</th>
            <th scope="col" class="action text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach($users as $user){
                    ?>
                    <tr>
                        <td><?=$user["idUtilisateur"]?></td>
                        <td><?=$user["email"]?></td>
                        <td><?=$user["nom"]?></td>
                        <td><?=$user["prenom"]?></td>
                        <td>
                            <a href="modifUser.php?id=<?=$user["idUtilisateur"]?>">
                                <button type="button" class="btn btn-warning">Modifier</button>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            ?>
        </tbody>
        </table>
    </div>
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
                <input type="text" class="form-control" name="nom" id="nom" placeholder="Entrez votre nom" value="<?=$user->getNom()?>" required>
            </div>

            <div class="form-group my-3">
                <label for="prenom" class="mb-1">Prénom : </label>
                <input type="text" class="form-control" name="prenom" id="prenom" placeholder="Entrez votre prénom" value="<?=$user->getPrenom()?>" required>
            </div>

            <div class="form-group my-3">
                <label for="age" class="mb-1">Âge : </label>
                <input type="number" class="form-control" name="age" id="age" min=0 placeholder="Entrez votre âge" value="<?=$user->getAge()?>" required>
            </div>

            <div class="form-group text-center mt-4">
                <button type="submit" class="btn btn-warning">Modifier</button>

                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModal">
                Supprimer
                </button>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
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