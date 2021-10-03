var divMap = document.getElementById('map');
var map = L.map('map').setView([divMap.dataset.lat, divMap.dataset.lng], divMap.dataset.zoom);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var lines = [];
var markers = [];

Array.from(document.querySelectorAll('.js-marker')).forEach((item) => {
  lines.push([item.dataset.lat, item.dataset.lng]);
  var marker = L.marker([item.dataset.lat, item.dataset.lng])
  marker['id'] = item.dataset.id;
  marker.bindPopup(
      `<div id="` + item.dataset.id + `" class='popup-container'>
          <div>Souhaitez-vous vous rendre à ` + item.dataset.name + ` ?</div>
          <div>
              <a href="` + (item.dataset.hebergement ? 'hebergementDescription' : 'hebergementVille') + `.php?` + (item.dataset.hebergement ? 'idHebergement=' : 'idVille=') + item.dataset.id + `" class='btn btn-success btn-sm popup-a'>J'y vais</a>
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