var idHebergement = document.getElementById('hebergement-description-container').dataset.idhebergement;
var submitButton = document.getElementById('submit');
var submitButtonYes = document.getElementById('submitYes');
var submitButtonNo = document.getElementById('submitNo');
var hiddenDiv = document.getElementById('hidden');
var nbJours = document.getElementById('nbJours');
var prix = document.getElementById('prix');
var prixHebergement = document.getElementById('prixHebergement').dataset.prix;
var diff_jours = null;
var alertDanger = document.getElementById('alert-danger');
var idCalendars = document.getElementById('calendar-container');
var allTds = idCalendars.querySelectorAll('td div');
var dateDebut = document.getElementsByClassName('date-debut')[0].id;
var dateFin = null;
var valid = false;
var nbJoursVoyage = 0;
var spanDateDepart = document.getElementById('dateDeDepartInfoUtilisateur');

var bookingDays = [];
idCalendars.querySelectorAll('td div.booking').forEach((item) => {
    bookingDays.push(item.id);
});

allTds.forEach(element => {

    if(element.classList.contains('selectable')){
        element.addEventListener('click', () => {

            if(!element.classList.contains('date-fin')){
                var dateFinDiv = document.getElementsByClassName('date-fin')[0];
                if(dateFinDiv != undefined){
                    dateFinDiv.classList.remove('date-fin');
                }
                element.classList.add('date-fin');
                dateFin = document.getElementsByClassName('date-fin')[0].id;
            }

            var date1 = new Date(dateDebut); 
            var date2 = new Date(dateFin); 
            date2.setDate(date2.getDate() + 1);
            var diff_temps = date2.getTime() - date1.getTime(); 
            diff_jours = diff_temps / (1000 * 3600 * 24);

            validity(dateDebut, diff_jours);
            
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

var heart = document.getElementsByClassName('fa-heart')[0];
heart.addEventListener('click', () => {
    favoris();
})

async function favoris(){
    var response = await fetch("../API/apiway.php?demande=favoris");
    var favoris = await response.json();
    if(favoris.status == "added"){
        heart.classList.remove('far');
        heart.classList.add('fas');
    }
    if(favoris.status == "deleted"){
        heart.classList.remove('fas');
        heart.classList.add('far');
    }
}

async function validity(dateDebut, diff_jours){
    var response = await fetch("../API/apiway.php?demande=checkBooking&da=" + dateDebut + "&nbj=" + diff_jours);
    var check = await response.json();
   
    if(check.code == 402){
        valid = false;
        alertDanger.classList.remove('d-none');
        alertDanger.innerHTML = check.message;
        nbJours.innerHTML = "?";
        prix.innerHTML = "?";
        spanDateDepart.innerHTML = "?";
        return false;
    }
    if(check.code == 200){
        valid = true;
        if(!alertDanger.classList.contains('d-none')){
            alertDanger.classList.add('d-none')
        }
        if(diff_jours > 1){
            nbJours.innerHTML = diff_jours + " nuits";
        } else {
            nbJours.innerHTML = diff_jours + " nuit";
        }
        nbJoursVoyage = diff_jours;
        prix.innerHTML = (prixHebergement * diff_jours).toFixed(2) + " €";
        var dateFin = new Date(dateDebut);
        dateFin = new Date(dateFin.setDate(dateFin.getDate() + diff_jours));
        spanDateDepart.innerHTML = dateFin.toISOString().slice(0, 10);

        return true;
    }

}

submitButton.addEventListener('click', () => {
    if (nbJoursVoyage != 0 && valid){
        submitButton.style.display = "none";
        hiddenDiv.classList.remove('d-none');
    }
})
submitButtonYes.addEventListener('click', () => {
    document.location.href="../controleurs/addHebergement.php?idHebergement=" + idHebergement + "&nbNuit=" + nbJoursVoyage + "&continue=1"; 
})
submitButtonNo.addEventListener('click', () => {
    document.location.href="../controleurs/addHebergement.php?idHebergement=" + idHebergement + "&nbNuit=" + nbJoursVoyage + "&continue=false";
})

