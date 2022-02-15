const map = document.querySelector('#map');
const allA = map.querySelectorAll(".map__image a")

// Polyfill du forEach car dans le cas présent on parcours un nodelist et non un tableau et ça ne marche que sur chrome pour l'instant
if (NodeList.prototype.forEach === undefined){
    NodeList.prototype.forEach = function (callback) {
        [].forEach.call(this, callback);
    }
}

// fonction pour mettre en couleur la zone sélectionner et retirer la couleur des autres
var activeArea = function (id) {
    map.querySelectorAll('.is-active').forEach(function (item){
        item.classList.remove('is-active');
    })
    if (id !== undefined){
        document.querySelector('#region-' + id).classList.add('is-active');
    }
}

allA.forEach(function (a) {
    a.addEventListener('mouseenter', function () {
        var id = this.id.replace('region-', '');
        activeArea(id);
    })
})

map.addEventListener('mouseover', function (){
    activeArea();
})
// ajout de l'apparition des infos de la régions
allA.forEach(function (a) {
    a.addEventListener('click', () => {
        regionDescription(a.dataset.idregion);
    })
})

async function regionDescription(idregion){
    var response = await fetch("../API/apiway.php?demande=region&idRegion=" + idregion);
    var description = await response.json();
    document.getElementById('description').innerHTML = description.description;
    document.getElementById('link').href = "createTravel.php?idRegion=" + idregion;
    var containerDescription = document.getElementById('description-region');
    var linkContainer = document.getElementById('link-container');
    if(linkContainer.classList.contains('d-none')){
        linkContainer.classList.remove('d-none');
    }

    switch (idregion){
        case "1":
            containerDescription.style.backgroundImage = "url('../assets/src/img/bretagne.jpg')";
            break;
        case "2":
            containerDescription.style.backgroundImage = "url('../assets/src/img/nantes.jpg')";
            break;
        case "3":
            containerDescription.style.backgroundImage = "url('../assets/src/img/centre-val-de-loire.jpg')";
            break;
        case "22":
            containerDescription.style.backgroundImage = "url('../assets/src/img/aquitaine.jpg')";
            break;
    }
}