var map = L.map('map').setView([47.2632836, -0.3299687], 8);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var lines = [];

Array.from(document.querySelectorAll('.js-marker')).forEach((item) => {
  lines.push([item.dataset.lat, item.dataset.lng]);
  L.marker([item.dataset.lat, item.dataset.lng]).addTo(map)
    .bindPopup("<div class='town-picture'></div>")
})

// il faudra créer un if (s'il existe des marker de point pour faire une ligne alors ... )

// var pointA = new L.LatLng(47.7632836, -0.3299687);
// var pointB = new L.LatLng(48.7632836, -0.3299687);
// var pointC = new L.LatLng(48.7632836, -0.9299687);
// var pointList = [pointA, pointB, pointC];

// var firstpolyline = new L.Polyline(pointList, {
//     color: 'red',
//     weight: 5,
//     opacity: 0.5,
//     smoothFactor: 1
// });
// firstpolyline.addTo(map);