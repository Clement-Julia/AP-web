var divMap = document.getElementById('map');
var map = L.map('map').setView([divMap.dataset.lat, divMap.dataset.lng], divMap.dataset.zoom);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var lines = [];
var markers = [];

Array.from(document.querySelectorAll('.js-marker')).forEach((item) => {
  lines.push([item.dataset.lat, item.dataset.lng]);

  var myIcon = L.icon({
    iconUrl: '../src/logos/ville.png',
    iconSize: [25,25],
    iconAnchor: [12.5,28]
  })
  var marker = L.marker([item.dataset.lat, item.dataset.lng], {icon: myIcon})

 
  marker.addEventListener('click', () => {
    document.getElementById(item.dataset.id).focus();
  })
  marker['id'] = item.dataset.id;
  marker.bindPopup(
      `<div id="` + item.dataset.id + `" class='popup-container'>
          <div>Souhaitez-vous vous rendre à ` + item.dataset.name + ` ?</div>
          <div>
              <a href="hebergementVille.php?` + 'idVille=' + item.dataset.id + `" class='btn btn-success btn-sm popup-a'>J'y vais</a>
          </div>
      </div>`)
  marker.addTo(map);
  markers.push(marker);
  
    item.addEventListener('click', () => {
      map.flyTo([item.dataset.lat, item.dataset.lng], item.dataset.zoom);
      setTimeout(() => {
        for(var i = 0; i < markers.length; ++i){
          if (marker["id"] === item.dataset.id) {
            marker.openPopup();
          }
        }
      },300)
    })
})

// On récupère les coordonnées des villes où l'utilisateur à réservé afin de créer une ligne entre chaque étape
if (document.getElementById('ligne-points') != undefined){
  var points = document.getElementById('ligne-points').querySelectorAll('div');
  var pointsList = [];
  points.forEach(item => {
    pointsList.push([item.dataset.lat, item.dataset.lng]);
  });

  if(pointsList.length >= 2){
    var firstpolyline = new L.Polyline(pointsList, {
      color: 'red',
      weight: 3,
      opacity: 0.5,
    });
    firstpolyline.addTo(map);
  }
}


var editButton = document.querySelectorAll('.editButton');

editButton.forEach((item) => {
    item.addEventListener('click', () => {
        item.parentElement.querySelector('.editForm').style.display = "flex";
        item.style.display = "none";
    })
})