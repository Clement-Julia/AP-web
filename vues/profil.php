<?php
require_once "header.php";
$user = new Utilisateur($_SESSION["idUtilisateur"]);
?>

<?php
    // error($_GET);
?>

<div class="container rounded bg-white mb-5">
    <div class="row">
        <div class="col-md-3 border-right">

            <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                <img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg">
                <span class="font-weight-bold"><?= $user->getPrenom() . " " . $user->getNom() ?></span>
                <span class="text-black-50"><?= $user->getEmail(); ?></span>
            </div>

            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                
                <h6 class="text-center text-decoration-underline">Compte</h6>
                <button class="border-profile text-muted nav-link active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true">
                <i class="fas fa-id-card me-2"></i>Informations personnelles
                </button>
                <button class="border-profile text-muted nav-link" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">
                    <i class="fas fa-shield-alt me-2"></i>Connexion & Sécurité
                </button>

                <h6 class="text-center text-decoration-underline mt-3">Achats</h6>
                <button class="border-profile text-muted nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">
                    <i class="fas fa-clock me-2"></i>Historique
                </button>
                <button class="border-profile text-muted nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">
                    <i class="fas fa-heart me-2"></i>Favoris
                </button>
            </div>
        </div>

        <div class="col-md-5 border-right">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade active show" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Informations personnelles</h4>
                        </div>
                        <form method="post" action="../../controleurs/user/updateUser.php?update=info">
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label class="labels">Nom</label>
                                    <input type="text" name="nom" class="form-control" placeholder="Nom" value="<?= $user->getNom() ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="labels">Prénom</label>
                                    <input type="text" name="prenom" class="form-control" value="<?= $user->getPrenom() ?>" placeholder="Prénom">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12 mt-3">
                                    <label class="labels">Âge</label>
                                    <input type="number" name="age" class="form-control" placeholder="Entrez votre âge" value="<?= $user->getAge() ?>">
                                </div>
                            </div>
                            <div class="mt-5 text-center">
                                <button class="btn btn-primary profile-button" type="submit">Sauvegarder les modifications</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                    <div class="p-3 py-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="text-right">Connexion & Sécurité</h4>
                        </div>
                        <div class="row mt-3">
                            <form method="post" action="../../controleurs/user/updateUser.php?update=co">
                                <div class="col-md-12">
                                    <label class="labels">Email</label>
                                    <input type="text" name="email" class="form-control" placeholder="Entrez votre email" value="<?= $user->getEmail() ?>">
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-5">
                                    <h4 class="text-right">Changer son mot-de-passe</h4>
                                </div>
                                <div class="col-md-12 mt-1">
                                    <label class="labels">Ancien mot-de-passe :</label>
                                    <input type="text" name="current_mdp" class="form-control" placeholder="Entrez votre mot-de-passe actuel" value="">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="labels">Nouveau mot-de-passe :</label>
                                    <input type="text" name="new_mdp" class="form-control" placeholder="Entrez votre nouveau mot-de-passe" value="">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="labels">Vérification nouveau mot-de-passe :</label>
                                    <input type="text" name="new_verif" class="form-control" placeholder="Entrez à nouveau votre nouveau mot-de-passe" value="">
                                </div>
                                <div class="mt-5 text-center">
                                    <button class="btn btn-primary profile-button" type="submit">Sauvegarder les modifications</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                    ...
                </div>
                <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                    ...
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 py-5">
                <p>Dernier voyage enregistrer ? Ou fait ? Ou les deux ?</p>
            </div>
        </div>
    </div>
</div>
</div>
</div>

<?php
require_once "footer.php";
?>