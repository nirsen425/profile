let deleteEmailElement = document.querySelector('.delete-email');
deleteEmailElement.addEventListener('click', function () {
    let emailConteiners = document.querySelectorAll('.email-container');
    if (emailConteiners.length > 1) {
        emailConteiners[emailConteiners.length-1].remove();
    }
});
