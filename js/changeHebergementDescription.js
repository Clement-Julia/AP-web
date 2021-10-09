var heart = document.getElementsByClassName('fa-heart')[0];
heart.addEventListener('click', () => {
    favoris();
})

async function favoris(){
    var response = await fetch("../API/apiway.php?demande=favoris");
    var favoris = await response.json();
    if(favoris.status == "added"){
        heart.classList.remove('far');
        heart.classList.add('fas');
    }
    if(favoris.status == "deleted"){
        heart.classList.remove('fas');
        heart.classList.add('far');
    }
}