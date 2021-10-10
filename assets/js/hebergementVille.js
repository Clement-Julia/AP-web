var divMap = document.getElementById('map');
var map = L.map('map').setView([divMap.dataset.lat, divMap.dataset.lng], divMap.dataset.zoom);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var markers = [];

Array.from(document.querySelectorAll('.js-marker')).forEach((item) => {
  var myIcon = L.divIcon({className: 'my-div-icon', html: item.dataset.price + " €", iconAnchor: [25,0]});
  var marker = L.marker([item.dataset.lat, item.dataset.lng], {icon: myIcon})

  marker.addEventListener('click', () => {
    document.getElementById(item.dataset.id).focus();
  })
  marker['id'] = item.dataset.id;
  marker.bindPopup(
      `<div id="` + item.dataset.id + `" class='popup-container'>
          <div>Souhaitez-vous vous rendre à ` + item.dataset.name + ` ?</div>
          <div>
              <a href="hebergementDescription.php?idHebergement=` + item.dataset.id + `" class='btn btn-success btn-sm popup-a'>J'y vais</a>
          </div>
      </div>`)
  marker.addTo(map);
  L.DomUtil.addClass(marker._icon, 'id-' + item.dataset.id);
  markers.push(marker);

    item.addEventListener('mouseenter', () => {
      var markdiv = document.getElementsByClassName('id-' + item.dataset.id)[0];
      markdiv.classList.add('mouse-enter');
    })
    item.addEventListener('mouseleave', () => {
      var markdiv = document.getElementsByClassName('id-' + item.dataset.id)[0];
      markdiv.classList.remove('mouse-enter');
    })

    
    item.addEventListener('click', () => {
      setTimeout(() => {
        for(var i = 0; i < markers.length; ++i){
          if (marker["id"] === item.dataset.id) {
            marker.openPopup();
          }
        }
      },300)
    })
})



async function getActivitesAndAddMarker(){
  var response = await fetch("../API/apiway.php?demande=activites");
  var activites = await response.json().then((response) => {

      if(Object.entries(response).length != 0 && response.code != 401){

          response.forEach((item) => {

            Object.entries(item).forEach(([key, value]) => {
              var myIcon = L.icon({
                iconUrl: '../src/logos/' + item.icon,
                iconSize: [30,30],
                iconAnchor: [15,33]
              })
              var marker = L.marker([item.latitude, item.longitude], {icon: myIcon})
              marker.bindPopup(
                  `<div class='popup-container'>
                      <div>` + item.description + `</div>
                  </div>`)
              marker.addTo(map);

          });

        })

      }

  });

}
getActivitesAndAddMarker()