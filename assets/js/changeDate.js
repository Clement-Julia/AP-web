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

            // Le système de booléen permet de savoir qui on selectionne avant de faire le changement réel de position avec le deuxième click
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

            // Si on passe le rond du début en position devant, leur rôle s'inverse pour garde la bonne temporalité
            if (dateFin < dateDebut){
                var temp = dateFin;
                dateFin = dateDebut;
                dateDebut = temp;
                dateDebutDiv = document.getElementsByClassName('date-debut')[0];
                dateFinDiv = document.getElementsByClassName('date-fin')[0];
                dateDebutDiv.classList.remove('date-debut');
                dateDebutDiv.classList.add('date-fin');
                dateFinDiv.classList.remove('date-fin');
                dateFinDiv.classList.add('date-debut');
            }
            dStart.innerHTML = moment(dateDebut).format('DD/MM/YYYY');
            dEndPlusUnJour = new Date(dateFin);
            dEndPlusUnJour.setDate(dEndPlusUnJour.getDate() + 1);
            var dateDepart = moment(dEndPlusUnJour.toISOString().slice(0, 10)).format('DD/MM/YYYY')

            dEnd.innerHTML = dateDepart
            // On test avec les infos du calendrier si les dates ne sont pas prise (petite vérif sympa pour le client)
            var date1 = new Date(dateDebut); 
            var date2 = new Date(dateFin);
            // date1 = moment(date1).format('YYYY-MM-DD');
            var eachDays = getDates(date1, date2)
            var indisponible = false;
            eachDays.forEach((item) => {
                var find = bookingDays.find(element => element == item);
                if(find != undefined){
                    indisponible = true;
                }
            })

            // on transforme la différence de temps en un nombre de jour
            var diff_temps = date2.getTime() - date1.getTime(); 
            diff_jours = (diff_temps / (1000 * 3600 * 24)) + 1;

            // Cette condition permet d'éviter une requête fetch à tous les clicks alors que seul le deuxième click de sélection permet un changement et demande donc de vérifier
            if((element.id == dateDebut || element.id == dateFin) && element.dataset.bool == "False"){
                validity();
            }

            // Si indisponible alors ça veut dire qu'une partie des dates sélectionnées sont déjà prise
            if(!indisponible){
                nbJours.innerHTML = diff_jours;
                prix.innerHTML = (prixHebergement * diff_jours).toFixed(2) + " €";
            } else {
                dStart.innerHTML = "Indisponible";
                dEnd.innerHTML = "Indisponible";
                nbJours.innerHTML = "Indisponible";
                prix.innerHTML = "Indisponible";
            }
            
        })
    }
});

// Permet de récupérer chaque date de l'interval de date donnée
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

// La fonction async qui interroge si c'est valide (ça reste une vérification côté JS, la vraie est dans les contrôleurs)
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