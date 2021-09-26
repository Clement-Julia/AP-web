var editButton = document.querySelectorAll('.editButton');

editButton.forEach((item) => {
    item.addEventListener('click', () => {
        item.parentElement.querySelector('.editForm').style.display = "flex";
        item.style.display = "none";
    })
})

