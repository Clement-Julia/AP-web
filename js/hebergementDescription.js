var nbNuits = 0;
var prix = document.getElementById('prix').dataset.prix;
var lastDayOfMonth = document.getElementById('table1').dataset.nbjour;
var travelStartDate = document.getElementById('table1').dataset.date;
var th = document.getElementById('table1');
var tds = th.querySelectorAll('td');
var th2 = document.getElementById('table2');
var tds2 = th2.querySelectorAll('td');


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
            document.getElementById('nuits').innerHTML = nbNuits;
            document.getElementById('prix').innerHTML = prix;
            document.getElementById('total').innerHTML = prix * nbNuits;
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
            document.getElementById('nuits').innerHTML = nbNuits;
            document.getElementById('prix').innerHTML = prix;
            document.getElementById('total').innerHTML = prix * nbNuits;
            
        })
    }
});



