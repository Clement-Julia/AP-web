var submit = document.getElementById('submit')
var input = document.getElementById('start-date');

submit.addEventListener('click', () => {
    var inputValue = input.value;
    if (inputValue != null && inputValue != ""){
        var split = inputValue.split('-');
        document.location.href="../controleurs/startTravelTime.php?jour=" + split[2] + "&mois=" + split[1] + "&annee=" + split[0]; 
    } else {
        input.style.border = "1px solid red";
    }
})
