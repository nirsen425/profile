let addTelephoneElement = document.querySelector('.add-telephone');
addTelephoneElement.addEventListener("click", function () {
    let radioButtonValue = this.previousElementSibling.querySelector('input[type="radio"]')
        .getAttribute('value');
    radioButtonValue++;
    let html = '<div class="telephone-container">' +
                    '<input type="text" name="telephone[]" id="telephone" placeholder="8**********">' +
                    '<select name="telephone-type[]">' +
                        '<option value="1">Мобильный</option>' +
                        '<option value="2">Рабочий</option>' +
                        '<option value="3">Домашний</option>' +
                    '</select>' +
                    '<input type="radio" name="main-telephone" value="' + radioButtonValue + '">' +
                    '<b>выбрать основным</b>' +
                '</div>';
    this.insertAdjacentHTML('beforebegin', html);
});