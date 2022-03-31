ListVille = document.getElementById("selectVille");
ListActivite = document.getElementById("selectActivite");

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
                option.innerHTML = item.description;
                ListActivite.append(option);
            })
        }else{
            var option = document.createElement('option');
            option.innerHTML = "Il n'y a aucune activité pour cette ville...";
            option.disabled = true;
            ListActivite.append(option);
        }
        $('#selectActivite').selectpicker('refresh');
    });
}