<?php


class CreateProfileFormValidator
{
    private $validation;
    private $data;

    public function __construct($data)
    {
        $this->validation = new Validation();
        $this->data = $data;
    }

    public function validateData()
    {
        $this->validateName();
        $this->validatePatronymic();
        $this->validateSurname();
        $this->validateEmail();
        $this->validateKeyMainEmail();
        $this->validateTelephone();
        $this->validateKeyMainTelephone();

        $errors = $this->validation->getErrors();

        return $errors;
    }

    private function validateName()
    {
        $nameValidators = [
            new RequiredValidator('Имя обязательно для заполнения')
        ];

        $this->validation->isValid($this->data['name'], $nameValidators, 'name');
    }

    private function validatePatronymic()
    {
        $patronymicValidators = [
            new RequiredValidator('Отчество обязательно для заполнения')
        ];

        $this->validation->isValid($this->data['patronymic'], $patronymicValidators, 'patronymic');
    }

    private function validateSurname()
    {
        $surnameValidators = [
            new RequiredValidator('Фамилия обязательна для заполнения')
        ];

        $this->validation->isValid($this->data['surname'], $surnameValidators, 'surname');
    }

    private function validateEmail()
    {
        $this->validation->isValid($this->data['email'], [new UniqueArrayValuesValidator('Email\'ы не должны повторяться')], 'email');

        $emailValidators = [
            new EmailValidator('Некорректный email')
        ];

        foreach ($this->data['email'] as $email) {
            array_push($emailValidators, new UniqueValidator("Такой email: $email занят", 'emails', 'title'));
            $this->validation->isValid($email, $emailValidators, 'email');
            array_pop($emailValidators);
        }
    }

    private function validateKeyMainEmail()
    {
        $emailsCount = count($this->data['email']);

        $mainEmailValidators = [
            new RangeValidator('Не выбран основной email', 0, --$emailsCount)
        ];

        $this->validation->isValid($this->data['main-email'], $mainEmailValidators, 'main-email');
    }

    private function validateTelephone()
    {
        $telephoneValidators = [
            new RequiredValidator('Не указан телефон')
        ];

        $this->validation->isValid($this->data['telephone'], [new UniqueArrayValuesValidator('Телефоны не должны повторяться')], 'telephone');

        foreach ($this->data['telephone'] as $telephone) {
            array_push($telephoneValidators, new UniqueValidator("Такой телефон: $telephone занят", 'telephones', 'title'));
            array_push($telephoneValidators, new StringRegExpValidator("Неверный формат телефона: $telephone", '#^8\d{10}$#'));
            $this->validation->isValid($telephone, $telephoneValidators, 'telephone');
            array_pop($telephoneValidators);
            array_pop($telephoneValidators);
        }
    }

    private function validateKeyMainTelephone()
    {
        $telephonesCount = count($this->data['telephone']);

        $mainTelephoneValidators = [
            new RangeValidator('Не выбран основной телефон', 0, --$telephonesCount)
        ];

        $this->validation->isValid($this->data['main-telephone'], $mainTelephoneValidators, 'main-telephone');
    }
}