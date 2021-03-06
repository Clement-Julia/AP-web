<?php
require_once "header.php";

$date = new DateTime();

if(isset($_GET["success"])){
    ?>
    <div class="container alert alert-success mt-3">
        Vous avez bien été inscrit !
    </div>
    <?php
}
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<style>

    html{
        height: 100%;
    }

    body{
        background-image: url('../assets/src/img/background/inscription.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center center;
        height: 100%;
        font-family: 'Numans', sans-serif;
        color: white;
    }

    body:after {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        content: '';
        background: #000;
        opacity: .3;
        z-index: -1;
        <?= (!empty($_GET["erreur"]) && $_GET["erreur"] == "all") ? "height: 850px;" : "height: 100%;" ?>
    }

    .container{
        @media (min-height: 720px) {
            body:after {
                background-size: auto;
                height: 850px;
            }
        }
    }

    @media only screen and (hover: none) and (pointer: coarse) {
        body:after {
            background-size: auto;
            height: 900px;
        }
    }

    form{
        min-width: 30%;
    }

    .form-input{
        width: 100%;
        background: rgb(0 0 0 / 0%);
        border: none;
        height: 50px;
        color: rgb(255 255 255) !important;
        border: 1px solid rgb(0 0 0 / 0%);
        background: rgb(255 255 255 / 8%);
        border-radius: 40px;
        padding-left: 20px;
        padding-right: 20px;
        -webkit-transition: 0.3s;
        -o-transition: 0.3s;
        transition: 0.3s;
    }

    .form-input:hover, .form-input:focus {
        background: transparent;
        outline: none;
        -webkit-box-shadow: none;
        box-shadow: none;
        border-color: rgba(255, 255, 255, 0.4);
    }

    .form-input:focus {
        border-color: rgba(255, 255, 255, 0.4);
    }

    .form-input::placeholder {
        color: rgba(255, 255, 255, 0.8) !important;
        opacity: 1;
    }

    .form-label{
        color: rgb(251 206 181);
    }

    .form-checkbox input:checked ~ .form-checkbox:after{
        color: rgb(251 206 181);
    }

    .checkbox-wrap {
        display: block;
        position: relative;
        padding-left: 30px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .checkbox-wrap input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkmark {
        position: absolute;
        top: 0;
        left: 0;
    }

    .checkmark:after {
        content: "\f0c8";
        font-family: "FontAwesome";
        position: absolute;
        color: rgba(255, 255, 255, 0.1);
        font-size: 20px;
        margin-top: -4px;
        -webkit-transition: 0.3s;
        -o-transition: 0.3s;
        transition: 0.3s; }
        @media (prefers-reduced-motion: reduce) {
            .checkmark:after {
            -webkit-transition: none;
            -o-transition: none;
            transition: none;
        }
    }

    .checkbox-wrap input:checked ~ .checkmark:after {
        display: block;
        content: "\f14a";
        font-family: "FontAwesome";
        color: rgba(0, 0, 0, 0.2);
    }

    .form-checkbox {
        color: #fbceb5;
    }

    .form-checkbox input:checked ~ .checkmark:after {
        color: #fbceb5;
    }

    .form-btn{
        min-height: 40px;
        background: rgb(251 206 181) !important;
        border: 1px solid rgb(251 206 181) !important;
        color: rgb(0 0 0) !important;
        border-radius: 40px;
        box-shadow: none !important;
        font-size: 15px;
        text-transform: uppercase;
    }

    .invalid-feedback {
        color: rgb(245 73 89);
    }

    .valid-feedback {
        color: rgb(52 235 150);
    }

    #connexion, #inscription{
        color: white !important;
    }

    ::-webkit-calendar-picker-indicator {
    filter: invert(1);
}
</style>

<div class="container mt-3 d-flex flex-column align-items-center" id="container">
    <h1>Inscription :</h1>
    <form method="POST" action = "../controleurs/inscription.php" class="needs-validation" novalidate>

        <div class="form-group my-3">
            <input type="text" class="form-input <?=(!empty($_GET["erreur"]) && $_GET["erreur"] == "all") ? "is-invalid" : ""?>" name="nom" id="nom" placeholder="Nom" value="<?=(!empty($_POST)) ? $_POST["nom"] : ""?>" required>

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Nom incorrect</div>
        </div>

        <div class="form-group my-3">
            <input type="text" class="form-input <?=(!empty($_GET["erreur"]) && $_GET["erreur"] == "all") ? "is-invalid" : ""?>" name="prenom" id="prenom" placeholder="Prénom" required>

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Prénom incorrect</div>
        </div>

        <div class="form-group my-3">
            <input type="password" class="form-input <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "mdp")) ? "is-invalid" : ""?>" name="mdp" id="mdp" placeholder="Mot de passe" required>
            <button type="button" class="popup-info errspan text-white" data-bs-toggle="tooltip" data-bs-html="true" title="<u>Doit contenir</u> :<br>-12 caractères<br>-Une majuscule<br>-Une minuscule<br>-Un chiffre<br>-Un caractère spécial">
                <i class="fas fa-info-circle"></i>
            </button>

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Votre mot-de-passe ne remplit pas les conditions</div>
        </div>

        <div class="form-group my-3">
            <input type="password" class="form-input <?=(!empty($_GET["erreur"]) && $_GET["erreur"] == "all") ? "is-invalid" : ""?>" name="mdpVerif" id="mdpVerif" placeholder="Vérification du mot de passe" required>

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Les mots-de-passe ne sont pas similaire</div>
        </div>

        <div class="form-group my-3">
            <input type="date" class="form-input <?=(!empty($_GET["erreur"]) && $_GET["erreur"] == "all" || !empty($_GET["erreur"]) && $_GET["erreur"] == "age") ? "is-invalid" : ""?>" name="age" id="age" min=0 placeholder="Date de naissance" max="<?= $date->format('Y-m-d') ?>" required>

            <div class="valid-feedback">Ok !</div>
            <div class="invalid-feedback">Date incorrect</div>
        </div>

        <div class="form-group my-3">
            <input type="email" class="form-input <?=(!empty($_GET["erreur"]) && ($_GET["erreur"] == "all" || $_GET["erreur"] == "email")) ? "is-invalid" : ""?>" name="email" id="email" placeholder="Email" required>

            <div class="valid-feedback">Ok !</div>
            <?=(!empty($_GET["erreur"]) && $_GET["erreur"] == "email") ? "<div class='invalid-feedback'>Cet email existe déjà</div>" : "<div class='invalid-feedback'>Email incorrect</div>"?>
        </div>

        <div class="form-check">
            <label class="checkbox-wrap form-checkbox <?=(!empty($_GET["erreur"]) && $_GET["erreur"] == "all") ? "is-invalid" : ""?>">J'accepte les <a class="form-label text-decoration-underline" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#exampleModal">conditions générales</a> d'utilisation
                <input type="checkbox" id="invalidCheck" class="<?=(!empty($_GET["erreur"]) && $_GET["erreur"] == "all") ? "is-invalid" : ""?>" checked required>
                <span class="checkmark"></span>
            </label>
            <div class="invalid-feedback">
                Vous devez accepter les conditions générales pour vous inscrire
            </div>

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Mentions légales et conditions générales d'utilisation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                En vigueur au <b>01/09/2021</b> <br>
                                

                                Les présentes conditions générales d'utilisation (dites « CGU ») ont pour objet l'encadrement juridique des modalités de mise à disposition du site et des services par DES GÉNIES  et de définir les conditions d’accès et d’utilisation des services par « l'Utilisateur ».
                                Les présentes CGU sont accessibles sur le site à la rubrique «CGU».

                                Toute inscription ou utilisation du site implique l'acceptation sans aucune réserve ni restriction des présentes CGU par l’utilisateur. Lors de l'inscription sur le site via le Formulaire d’inscription, chaque utilisateur accepte expressément les présentes CGU en cochant la case précédant le texte suivant : « Je reconnais avoir lu et compris les CGU et je les accepte ».
                                En cas de non-acceptation des CGU stipulées dans le présent contrat, l'Utilisateur se doit de renoncer à l'accès des services proposés par le site.
                                http://localhost/Projet/PPE/main/vues/  se réserve le droit de modifier unilatéralement et à tout moment le contenu des présentes CGU.

                                Article 1 : Les mentions légales

                                L'édition du site http://localhost/Projet/PPE/main/vues/ est assurée par la Société NOUS DES GÉNIES au capital de 1000000000 euros, immatriculée au RCS de Paris sous le numéro 5454151, dont le siège social est situé au Paris
                                Numéro de téléphone 3615
                                Adresse e-mail : wow@hotmail.com.
                                Le Directeur de la publication est : Moi même

                                L'hébergeur du site http://localhost/Projet/PPE/main/vues/ est la société wamp, dont le siège social est situé au aucune idée, avec le numéro de téléphone : ah.

                                ARTICLE 2 : Accès au site

                                Le site http://localhost/Projet/PPE/main/vues/  permet à l'Utilisateur un accès gratuit aux services suivants :
                                Le site internet propose les services suivants :
                                _______________
                                Le site est accessible gratuitement en tout lieu à tout Utilisateur ayant un accès à Internet. Tous les frais supportés par l'Utilisateur pour accéder au service (matériel informatique, logiciels, connexion Internet, etc.) sont à sa charge.

                                L’Utilisateur non membre n'a pas accès aux services réservés. Pour cela, il doit s’inscrire en remplissant le formulaire. En acceptant de s’inscrire aux services réservés, l’Utilisateur membre s’engage à fournir des informations sincères et exactes concernant son état civil et ses coordonnées, notamment son adresse email.
                                Pour accéder aux services, l’Utilisateur doit ensuite s'identifier à l'aide de son identifiant et de son mot de passe qui lui seront communiqués après son inscription.
                                Tout Utilisateur membre régulièrement inscrit pourra également solliciter sa désinscription en se rendant à la page dédiée sur son espace personnel. Celle-ci sera effective dans un délai raisonnable.
                                Tout événement dû à un cas de force majeure ayant pour conséquence un dysfonctionnement du site ou serveur et sous réserve de toute interruption ou modification en cas de maintenance, n'engage pas la responsabilité de http://localhost/Projet/PPE/main/vues/. Dans ces cas, l’Utilisateur accepte ainsi ne pas tenir rigueur à l’éditeur de toute interruption ou suspension de service, même sans préavis.
                                L'Utilisateur a la possibilité de contacter le site par messagerie électronique à l’adresse email de l’éditeur communiqué à l’ARTICLE 1.

                                ARTICLE 3 : Collecte des données

                                Le site assure à l'Utilisateur une collecte et un traitement d'informations personnelles dans le respect de la vie privée conformément à la loi n°78-17 du 6 janvier 1978 relative à l'informatique, aux fichiers et aux libertés. Le site est déclaré à la CNIL sous le numéro 05151215.
                                En vertu de la loi Informatique et Libertés, en date du 6 janvier 1978, l'Utilisateur dispose d'un droit d'accès, de rectification, de suppression et d'opposition de ses données personnelles. L'Utilisateur exerce ce droit :
                                ·         via son espace personnel ;

                                ARTICLE 4 : Propriété intellectuelle

                                Les marques, logos, signes ainsi que tous les contenus du site (textes, images, son…) font l'objet d'une protection par le Code de la propriété intellectuelle et plus particulièrement par le droit d'auteur.

                                L'Utilisateur doit solliciter l'autorisation préalable du site pour toute reproduction, publication, copie des différents contenus. Il s'engage à une utilisation des contenus du site dans un cadre strictement privé, toute utilisation à des fins commerciales et publicitaires est strictement interdite.
                                Toute représentation totale ou partielle de ce site par quelque procédé que ce soit, sans l’autorisation expresse de l’exploitant du site Internet constituerait une contrefaçon sanctionnée par l’article L 335-2 et suivants du Code de la propriété intellectuelle.
                                Il est rappelé conformément à l’article L122-5 du Code de propriété intellectuelle que l’Utilisateur qui reproduit, copie ou publie le contenu protégé doit citer l’auteur et sa source.
                                
                                ARTICLE 5 : Responsabilité

                                Les sources des informations diffusées sur le site http://localhost/Projet/PPE/main/vues/ sont réputées fiables mais le site ne garantit pas qu’il soit exempt de défauts, d’erreurs ou d’omissions.
                                Les informations communiquées sont présentées à titre indicatif et général sans valeur contractuelle. Malgré des mises à jour régulières, le site http://localhost/Projet/PPE/main/vues/ ne peut être tenu responsable de la modification des dispositions administratives et juridiques survenant après la publication. De même, le site ne peut être tenue responsable de l’utilisation et de l’interprétation de l’information contenue dans ce site.
                                L'Utilisateur s'assure de garder son mot de passe secret. Toute divulgation du mot de passe, quelle que soit sa forme, est interdite. Il assume les risques liés à l'utilisation de son identifiant et mot de passe. Le site décline toute responsabilité.
                                Le site http://localhost/Projet/PPE/main/vues/ ne peut être tenu pour responsable d’éventuels virus qui pourraient infecter l’ordinateur ou tout matériel informatique de l’Internaute, suite à une utilisation, à l’accès, ou au téléchargement provenant de ce site.
                                La responsabilité du site ne peut être engagée en cas de force majeure ou du fait imprévisible et insurmontable d'un tiers.

                                ARTICLE 6 : Liens hypertextes

                                Des liens hypertextes peuvent être présents sur le site. L’Utilisateur est informé qu’en cliquant sur ces liens, il sortira du site http://localhost/Projet/PPE/main/vues/. Ce dernier n’a pas de contrôle sur les pages web sur lesquelles aboutissent ces liens et ne saurait, en aucun cas, être responsable de leur contenu.

                                ARTICLE 7 : Cookies

                                L’Utilisateur est informé que lors de ses visites sur le site, un cookie peut s’installer automatiquement sur son logiciel de navigation.
                                Les cookies sont de petits fichiers stockés temporairement sur le disque dur de l’ordinateur de l’Utilisateur par votre navigateur et qui sont nécessaires à l’utilisation du site http://localhost/Projet/PPE/main/vues/. Les cookies ne contiennent pas d’information personnelle et ne peuvent pas être utilisés pour identifier quelqu’un. Un cookie contient un identifiant unique, généré aléatoirement et donc anonyme. Certains cookies expirent à la fin de la visite de l’Utilisateur, d’autres restent.
                                L’information contenue dans les cookies est utilisée pour améliorer le site http://localhost/Projet/PPE/main/vues/.
                                En naviguant sur le site, L’Utilisateur les accepte.
                                L’Utilisateur doit toutefois donner son consentement quant à l’utilisation de certains cookies.
                                A défaut d’acceptation, l’Utilisateur est informé que certaines fonctionnalités ou pages risquent de lui être refusées.
                                L’Utilisateur pourra désactiver ces cookies par l’intermédiaire des paramètres figurant au sein de son logiciel de navigation.

                                ARTICLE 8 : Publication par l’Utilisateur

                                Le site permet aux membres de publier les contenus suivants :
                                Images.
                                Dans ses publications, le membre s’engage à respecter les règles de la Netiquette (règles de bonne conduite de l’internet) et les règles de droit en vigueur.
                                Le site peut exercer une modération sur les publications et se réserve le droit de refuser leur mise en ligne, sans avoir à s’en justifier auprès du membre.
                                Le membre reste titulaire de l’intégralité de ses droits de propriété intellectuelle. Mais en publiant une publication sur le site, il cède à la société éditrice le droit non exclusif et gratuit de représenter, reproduire, adapter, modifier, diffuser et distribuer sa publication, directement ou par un tiers autorisé, dans le monde entier, sur tout support (numérique ou physique), pour la durée de la propriété intellectuelle. Le Membre cède notamment le droit d'utiliser sa publication sur internet et sur les réseaux de téléphonie mobile.
                                La société éditrice s'engage à faire figurer le nom du membre à proximité de chaque utilisation de sa publication.
                                Tout contenu mis en ligne par l'Utilisateur est de sa seule responsabilité. L'Utilisateur s'engage à ne pas mettre en ligne de contenus pouvant porter atteinte aux intérêts de tierces personnes. Tout recours en justice engagé par un tiers lésé contre le site sera pris en charge par l'Utilisateur.
                                Le contenu de l'Utilisateur peut être à tout moment et pour n'importe quelle raison supprimé ou modifié par le site, sans préavis.
                                
                                ARTICLE 9 : Droit applicable et juridiction compétente

                                La législation française s'applique au présent contrat. En cas d'absence de résolution amiable d'un litige né entre les parties, les tribunaux français seront seuls compétents pour en connaître.
                                Pour toute question relative à l’application des présentes CGU, vous pouvez joindre l’éditeur aux coordonnées inscrites à l’ARTICLE 1
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="approuved" class="btn btni" data-bs-dismiss="modal">Lu et approuvé</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group mt-4 row justify-content-center">
            <button type="submit" class="btn form-btn return col-8" name="submit" value="ON">Inscription</button>
        </div>

    </form>
</div>

<script>
    (function () {
    'use strict'

    var forms = document.querySelectorAll('.needs-validation');

    Array.prototype.slice.call(forms)
        .forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

            }

            form.classList.add('was-validated')
        }, false)
        })
    })()

    document.getElementById('approuved').addEventListener('click', function(e) {
        document.getElementById("invalidCheck").checked = true
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    document.addEventListener("DOMContentLoaded", function (){
        setTimeout(() =>{
            document.getElementById("mdp").value = "";
            document.getElementById("email").value = "";
        }, 550)
    })

</script>


<?php
require_once "footer.php";
?>