var hearts = document.getElementsByClassName('fa-heart');

Array.prototype.forEach.call(hearts,(element) => {
    element.addEventListener('click', () => {
        var idHebergement = element.id;
        favoris(idHebergement);
    })
});

async function favoris(idHebergement){
    var response = await fetch("../API/apiway.php?demande=favoris&idHebergement=" + idHebergement);
    var favoris = await response.json().then((response) => {
        var heart = document.getElementById(idHebergement);
        if(response.status == "added"){
            heart.classList.remove('far');
            heart.classList.add('fas');
        }
        if(response.status == "deleted"){
            heart.classList.remove('fas');
            heart.classList.add('far');
        }
    });
    
}