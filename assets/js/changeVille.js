var divMap = document.getElementById('map');
var map = L.map('map').setView([divMap.dataset.lat, divMap.dataset.lng], divMap.dataset.zoom);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var lines = [];

Array.from(document.querySelectorAll('.js-marker')).forEach((item) => {
  lines.push([item.dataset.lat, item.dataset.lng]);
  L.marker([item.dataset.lat, item.dataset.lng]).addTo(map)
    .bindPopup(
      `<div id="` + item.dataset.id + `" class='popup-container'>
          <div>Souhaitez-vous vous rendre à ` + item.dataset.name + ` ?</div>
          <div>
              <a href="` + (item.dataset.hebergement ? 'changeHebergementDescription' : 'changeHebergement') + `.php?` + (item.dataset.hebergement ? 'idHebergement=' : 'idVille=') + item.dataset.id + `" class='btn btn-success btn-sm popup-a'>J'y vais</a>
          </div>
      </div>`)
})

if (document.getElementById('ligne-points') != undefined){
  var points = document.getElementById('ligne-points').querySelectorAll('div');
  var pointsList = [];
  points.forEach(item => {
    pointsList.push([item.dataset.lat, item.dataset.lng]);
  });
  console.log(pointsList);
  if(pointsList.length >= 2){
    var firstpolyline = new L.Polyline(pointsList, {
      color: 'red',
      weight: 5,
      opacity: 0.5,
    });
    firstpolyline.addTo(map);
  }
  
}