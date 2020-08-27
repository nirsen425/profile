let addEmailElement = document.querySelector('.add-email');
addEmailElement.addEventListener("click", function () {
    let radioButtonValue = this.previousElementSibling.querySelector('input[type="radio"]')
        .getAttribute('value');
    radioButtonValue++;
    let html = '<div class="email-container">' +
                    '<input type="text" name="email[]" id="email">' +
                    '<input type="radio" name="main-email" value="' + radioButtonValue + '">' +
                    '<b>выбрать основным</b>' +
                '</div>';
    this.insertAdjacentHTML('beforebegin', html);
});