var nbNuits = 0;
var prix = document.getElementById('prix').dataset.prix;
var lastDayOfMonth = document.getElementById('table1').dataset.nbjour;
var travelStartDate = document.getElementById('table1').dataset.date;
var th = document.getElementById('table1');
var tds = th.querySelectorAll('td');
var th2 = document.getElementById('table2');
var tds2 = th2.querySelectorAll('td');
var submitButton = document.getElementById('submit');


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
        // on redirige avec toutes les infos dans une page de controleur
        // il faudra vérifier côté back les paramètres 1 par 1 avant de valider
    }
})

