let deleteTelephoneElement = document.querySelector('.delete-telephone');
deleteTelephoneElement.addEventListener('click', function () {
    let telephoneConteiners = document.querySelectorAll('.telephone-container');
    if (telephoneConteiners.length > 1) {
        telephoneConteiners[telephoneConteiners.length-1].remove();
    }
});