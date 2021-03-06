$(document).ready(function() {
  var layerGroup;
  var latitude;
  var longitude;

  // Création de la map avec une position et un zoom fixé
  if(latitude && longitude){
    map = L.map('map').setView([latitude, longitude], 6);
  }else{
    map = L.map('map').setView([46.768196, 2.432664], 6);
  }
  layerGroup = L.layerGroup().addTo(map);

  // Génération des paramètres de map
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
    maxZoom: 18,
  }).addTo(map);

  // Récupération de la latitude et longitude on click
  map.on('click', function(e) {

    // On supprime le marqueur présent si l'utilisateur a déjà cliquer
    layerGroup.clearLayers();

    // Ajouter un marqueur à la map
    marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(layerGroup);

    // Ajouter une popeup à la marque
    marker.bindPopup("L'activité se situera ici").openPopup();

    $("#latitude").val(e.latlng.lat);
    $("#longitude").val(e.latlng.lng);
    $("#currentLatitude").val(e.latlng.lat);
    $("#currentLongitude").val(e.latlng.lng);
  });

  if($("#DataListVille").val()){
    latitude = $('#latitude').val();
    longitude = $('#longitude').val();
    map.setView([latitude, longitude], 12);
    marker = L.marker([latitude, longitude]).addTo(layerGroup);
    marker.bindPopup("L'activité se situe ici").openPopup();
  }

  $("#DataListVille").change(function () {
    if($("#DataListVille").val()){
      latitude = $("#DataListVille option:checked").data("latitude");
      longitude = $("#DataListVille option:checked").data("longitude");
      map.setView([latitude, longitude], 12);
    }
  })
});