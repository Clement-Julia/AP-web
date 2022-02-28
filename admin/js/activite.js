ListVille = document.getElementById("DataListVille");
ListActivite = document.getElementById("datalistActivites");

ListVille.addEventListener("change", () => {
    ListActivite.innerHTML = '';
    getActivites();
}, false);

async function getActivites(){
    var response = await fetch("../API/apiway.php?demande=adminActivites&ville="+ListVille.value);
    await response.json().then((response) => {
        if(Object.entries(response).length != 0 && response.code != 401){
            response.forEach((item) => {
                var option = document.createElement('option');
                option.value = item.description;
                ListActivite.appendChild(option);
            })
        }
    });
}