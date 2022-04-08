<?php
    $_SESSION['previous_location'] = basename($_SERVER["SCRIPT_FILENAME"]);
?>

<button type="button" id="cookie-alert" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-dark">
        <div class="modal-content">
        <div class="modal-header background-cookie">
            <h5 class="modal-title" id="exampleModalLabel">Salut c'est nous...<br><b>Les cookies !</b></h5>
        </div>
        <div class="modal-body border-b-none">
            <p>On a attendu d'être sûr que le contenu de ce site vous intéresse avant de vous déranger, mais on aimerait bien vous accompagner pendant votre visite...</p>
            <p>C'est ok pour vous ?</p>
        </div>
        <div class="modal-footer border-b-none p-0">
            <div class="row m-0" style="width: 100%">
                <button type="button" class="btn btn-outline-secondary btn-lg border-cookie col-6" data-bs-target="#information" data-bs-toggle="modal" data-bs-dismiss="modal">Plus d'infos</button>
                <form action="../controleurs/connectionCookies.php" method="post" class="col-6 p-0">
                    <input type="hidden" value="1" name="accept-cookies-modal">
                    <button type="submit" class="btn btn-outline-warning btn-lg border-cookie" style="width:100%">Accepter</button>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="information" tabindex="-1" aria-labelledby="informationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-muted">
        <div class="modal-content">
        <div class="modal-header background-cookie">
            <h5 class="modal-title" id="informationLabel">Salut c'est nous...<br><b>Les Cookies !</b></h5>
        </div>
        <div class="modal-body border-b-none">
            <p>Ce site utilise des cookies pour améliorer votre expérience de navigation. Parmi ces cookies, les cookies classés comme nécessaires sont stockés dans votre navigateur car ils sont aussi essentiels au fonctionnement des fonctionnalités de base du site. Nous utilisons également des cookies tiers qui nous aident à analyser et à comprendre comment vous utilisez ce site et vous maintenir connecté. Ces cookies ne seront stockés dans votre navigateur qu'avec votre consentement. Vous avez également la possibilité de désactiver ces cookies.</p>
        </div>
        <div class="modal-footer border-b-none p-0">
            <div class="row m-0" style="width: 100%">
                <button type="button" class="btn btn-outline-secondary btn-lg border-cookie col-6" data-bs-dismiss="modal">Refuser</button>
                <form action="../controleurs/connectionCookies.php" method="post" class="col-6 p-0">
                    <input type="hidden" value="1" name="accept-cookies-modal">
                    <button type="submit" class="btn btn-outline-warning btn-lg border-cookie" style="width:100%">Accepter</button>
                </form>
            </div>
        </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        document.getElementById("cookie-alert").click();
    });
</script>