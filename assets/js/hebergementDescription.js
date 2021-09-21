var idHebergement = document.getElementById('hebergement-description-container').dataset.idhebergement;
var nbNuits = 0;
var prix = document.getElementById('prix').dataset.prix;
var lastDayOfMonth = document.getElementById('table1').dataset.nbjour;
var travelStartDate = document.getElementById('table1').dataset.date;
var th = document.getElementById('table1');
var tds = th.querySelectorAll('td');
var th2 = document.getElementById('table2');
var tds2 = th2.querySelectorAll('td');
var submitButton = document.getElementById('submit');
var submitButtonYes = document.getElementById('submitYes');
var submitButtonNo = document.getElementById('submitNo');
var hiddenDiv = document.getElementById('hidden');

tds.forEach(item => {

    var date = item.querySelector('div').outerText;

    if (date > th.dataset.date){
        item.addEventListener('click', () => {

            tds.forEach(itemtwice => {
                if (itemtwice.classList.contains('calendar-toggle')){
                    itemtwice.classList.remove('calendar-toggle');
                }
            });

            tds2.forEach(itemtwice => {
                if (itemtwice.classList.contains('calendar-toggle')){
                    itemtwice.classList.remove('calendar-toggle');
                }
            });

            item.classList.add('calendar-toggle');
            nbNuits = Number(date) - Number(th.dataset.date);
            if (nbNuits > 1){
                document.getElementById('nuits').innerHTML = nbNuits + " nuits";
            } else {
                document.getElementById('nuits').innerHTML = nbNuits + " nuit";
            }
            document.getElementById('total').innerHTML = prix * nbNuits + " €";
        })
    }
});


tds2.forEach(item => {

    var date = item.querySelector('div').outerText;

    if (!item.classList.contains('calendar__overmonth')){
        item.addEventListener('click', () => {

            tds2.forEach(itemtwice => {
                if (itemtwice.classList.contains('calendar-toggle')){
                    itemtwice.classList.remove('calendar-toggle');
                }
            });

            tds.forEach(itemtwice => {
                if (itemtwice.classList.contains('calendar-toggle')){
                    itemtwice.classList.remove('calendar-toggle');
                }
            });

            item.classList.add('calendar-toggle');
            nbNuits = (Number(lastDayOfMonth) - Number(travelStartDate)) + Number(date);
            if (nbNuits > 1){
                document.getElementById('nuits').innerHTML = nbNuits + " nuits";
            } else {
                document.getElementById('nuits').innerHTML = nbNuits + " nuit";
            }
            document.getElementById('total').innerHTML = prix * nbNuits + "€";
            
        })
    }
});

submitButton.addEventListener('click', () => {
    if (prix != 0){
        submitButton.style.display = "none";
        hiddenDiv.classList.remove('d-none');
    }
})
submitButtonYes.addEventListener('click', () => {
    document.location.href="../controleurs/addHebergement.php?idHebergement=" + idHebergement + "&nbNuit=" + nbNuits + "&continue=1"; 
})
submitButtonNo.addEventListener('click', () => {
    document.location.href="../controleurs/addHebergement.php?idHebergement=" + idHebergement + "&nbNuit=" + nbNuits + "&continue=0"; 
})

