var inputDateDebut = document.getElementById('inputDateDebut');
var inputDateFin = document.getElementById('inputDateFin');
var dStart = document.getElementById('d-start');
var dEnd = document.getElementById('d-end');
var nbJours = document.getElementById('nbJours');
var prix = document.getElementById('prix');
var prixHebergement = document.getElementById('prixHebergement').dataset.prix;
var diff_jours = null;
var alertWarning = document.getElementById('alert-warning');
var alertDanger = document.getElementById('alert-danger');

var idCalendars = document.getElementById('cd-calendar-container');
var allTds = idCalendars.querySelectorAll('td div');
var dateDebut = document.getElementsByClassName('date-debut')[0].id;
var dateFin = document.getElementsByClassName('date-fin')[0].id;
var nbJoursVoyage = 0;

var bookingDays = [];
idCalendars.querySelectorAll('td div.booking').forEach((item) => {
    bookingDays.push(item.id);
});

allTds.forEach(element => {

    if(element.classList.contains('selectable')){
        element.addEventListener('click', () => {

            var dateDebutDiv = document.getElementsByClassName('date-debut')[0];
            var dateFinDiv = document.getElementsByClassName('date-fin')[0];

            if(dateDebutDiv.dataset.bool == "True"){
                dateDebutDiv.classList.remove('calendar-selected');
                dateDebutDiv.classList.remove('date-debut');
                dateDebutDiv.dataset.bool = "False";
                element.classList.add('date-debut');
                dateDebut = element.id;
            } else if (dateFinDiv.dataset.bool == "True"){
                dateFinDiv.classList.remove('calendar-selected');
                dateFinDiv.classList.remove('date-fin');
                dateFinDiv.dataset.bool = "False";
                element.classList.add('date-fin');
                dateFin = element.id;
            } else if(element.id == dateDebutDiv.id || element.id == dateFinDiv.id){
                element.dataset.bool = "True";
                element.classList.add('calendar-selected');
            }

            if (dateFin < dateDebut){
                var temp = dateFin;
                dateFin = dateDebut;
                dateDebut = temp;
            }

            dStart.innerHTML = dateDebut;
            dEnd.innerHTML = dateFin;

            // la faudra tester si les dates sont pas prise

            var date1 = new Date(dateDebut); 
            var date2 = new Date(dateFin); 
            var eachDays = getDates(date1, date2)
            var indisponible = false;
            eachDays.forEach((item) => {
                var find = bookingDays.find(element => element == item);
                if(find != undefined){
                    indisponible = true;
                }
            })

            var diff_temps = date2.getTime() - date1.getTime(); 
            diff_jours = diff_temps / (1000 * 3600 * 24);

            if((element.id == dateDebut || element.id == dateFin) && element.dataset.bool == "False"){
                validity();
            }

            if(!indisponible){
                nbJours.innerHTML = diff_jours;
                prix.innerHTML = prixHebergement * diff_jours;
            } else {
                dStart.innerHTML = "INDISPONIBLE";
                dEnd.innerHTML = "INDISPONIBLE";
                nbJours.innerHTML = "INDISPONIBLE";
                prix.innerHTML = "INDISPONIBLE";
            }
            
        })
    }
});

function getDates(startDate, stopDate) {
    var dateArray = [];
    var currentDate = moment(startDate);
    var stopDate = moment(stopDate);
    while (currentDate <= stopDate) {
        dateArray.push( moment(currentDate).format('YYYY-MM-DD') )
        currentDate = moment(currentDate).add(1, 'days');
    }
    return dateArray;
}

async function validity(){
    var response = await fetch("../API/apiway.php?demande=checkValidity&da=" + dateDebut + "&nbj=" + diff_jours);
    var check = await response.json();
   
    if(check.code == 401){
        alertWarning.classList.remove('d-none');
        alertWarning.innerHTML = check.message;
        inputDateDebut.value = dateDebut;
        inputDateFin.value = dateFin;
        if(!alertDanger.classList.contains('d-none')){
            alertDanger.classList.add('d-none')
        }
    }
    if(check.code == 402){
        alertDanger.classList.remove('d-none');
        alertDanger.innerHTML = check.message;
        inputDateDebut.value = null;
        inputDateFin.value = null;
        if(!alertWarning.classList.contains('d-none')){
            alertWarning.classList.add('d-none')
        }
    }
    if(check.code == 200){
        inputDateDebut.value = dateDebut;
        inputDateFin.value = dateFin;
        if(!alertDanger.classList.contains('d-none')){
            alertDanger.classList.add('d-none')
        }
        if(!alertWarning.classList.contains('d-none')){
            alertWarning.classList.add('d-none')
        }
    }

}