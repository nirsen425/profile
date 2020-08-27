<?php


class Profile
{
    private $email;
    private $telephone;
    private $db;

    public function __construct()
    {
        $this->email = new Email();
        $this->telephone = new Telephone();
        $this->db = Db::getConnection();
    }

    public function saveFullyCompletedProfile($data)
    {
        Session::saveFormDataInSession('profile-create');

        $this->validateDataForSaveProfile($data);

        try {
            $this->db->beginTransaction();

            $lastInsertIdProfile = $this->saveProfile($data['name'],
                $data['patronymic'], $data['surname']);

            $this->email->saveEmails($lastInsertIdProfile, $data['email'], $data['main-email']);

            $this->telephone->saveTelephones($lastInsertIdProfile, $data['telephone'],
                $data['telephone-type'], $data['main-telephone']);

            Session::deleteFormDataInSession('profile-create');

            $this->db->commit();
        } catch (Exception $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    private function validateDataForSaveProfile($data)
    {
        $telephonesCount = count($data['telephone']);
        $emailsCount = count($data['email']);

        $validationRules = [
            'name' => 'required',
            'patronymic' => 'required',
            'surname' => 'required',
            'email' => 'email|unique_array_values|unique:emails,title',
            'main-email' => 'between:0,' . --$emailsCount,
            'telephone' => 'required|telephones_format|unique_array_values|unique:telephones,title',
            'telephone-type' => 'between:1,3',
            'main-telephone' => 'between:0,' . --$telephonesCount,
        ];

        $errorMessages = [
            'name.required' => 'Имя обязательно для заполнения',
            'patronymic.required' => 'Отчество обязательно для заполнения',
            'surname.required' => 'Фамилия обязательна для заполнения',
            'email.email' => 'Некорректный email',
            'email.unique' => 'Такой email: :value занят',
            'email.unique_array_values' => "Email'ы не должны повторяться",
            'main-email.between' => 'Не выбран основной email',
            'telephone.required' => 'Не указан телефон',
            'telephone.unique' => 'Такой телефон: :value занят',
            'telephone.unique_array_values' => 'Телефоны не должны повторяться',
            'telephone.telephones_format' => 'Неверный формат телефона: :value',
            'main-telephone.between' => 'Не выбран основной телефон',
        ];

        Validation::validate($data, $validationRules, $errorMessages);
    }

    private function saveProfile($name, $patronymic, $surname)
    {
        $query = 'INSERT INTO profiles (name, patronymic, surname) VALUES (?, ?, ?)';
        $stmt = $this->db->prepare($query);
        $stmt->execute([$name, $patronymic, $surname]);
        $query = 'SELECT LAST_INSERT_ID() as lastInsertIdProfile';
        $lastInsertIdProfile = $this->db->query($query)->fetch(PDO::FETCH_ASSOC)['lastInsertIdProfile'];

        return $lastInsertIdProfile;
    }

    public function getAllProfiles()
    {
        $query = 'SELECT id, name, patronymic, surname FROM profiles';
        $stmt = $this->db->query($query);
        $profiles = $stmt->fetchAll();

        return $profiles;
    }

    public function getProfileById($profileId)
    {
        $query = "SELECT id, name, patronymic, surname FROM profiles WHERE id = $profileId";
        $stmt = $this->db->query($query);
        $profile = $stmt->fetch();

        return $profile;
    }

    public function updateFullyCompletedProfile($profileId, $data)
    {
        $this->validateDataForUpdateProfile($profileId, $data);

        try {
            $this->db->beginTransaction();

            $this->updateProfile($profileId, $data['name'],
                $data['patronymic'], $data['surname']);

            $this->email->changeEmailTable($profileId, $data['email'], $data['main-email']);

            $this->telephone->updateTelephones($profileId, $data['telephone'],
                $data['telephone-type'], $data['main-telephone']);

            $this->db->commit();
        } catch (Exception $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    private function validateDataForUpdateProfile($profileId, $data)
    {
        $telephonesCount = count($data['telephone']);
        $emailsCount = count($data['email']);

        $validationRules = [
            'name' => 'required',
            'patronymic' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique_array_values|unique:emails,title,' . $profileId . ',profile_id',
            'main-email' => 'between:0,' . --$emailsCount,
            'telephone' => 'required|telephones_format|unique_array_values|unique:telephones,title,' . $profileId . ',profile_id',
            'telephone-type' => 'between:1,3',
            'main-telephone' => 'between:0,' . --$telephonesCount,
        ];

        $errorMessages = [
            'name.required' => 'Имя обязательно для заполнения',
            'patronymic.required' => 'Отчество обязательно для заполнения',
            'surname.required' => 'Фамилия обязательна для заполнения',
            'email.email' => 'Некорректный email',
            'email.required' => 'Не заполнен email',
            'email.unique_array_values' => "Email'ы не должны повторяться",
            'email.unique' => 'Такой email: :value уже занят',
            'main-email.between' => 'Не выбран основной email',
            'telephone.required' => 'Не указан телефон',
            'telephone.unique_array_values' => "Телефоны не должны повторяться",
            'telephone.unique' => 'Такой телефон: :value уже занят',
            'telephone.telephones_format' => 'Неверный формат телефона: :value',
            'main-telephone.between' => 'Не выбран основной телефон',
        ];

        Validation::validate($data, $validationRules, $errorMessages);
    }

    private function updateProfile($profileId, $name, $patronymic, $surname)
    {
        $query = "UPDATE profiles SET name = ?, patronymic = ?, surname = ? WHERE id = $profileId";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$name, $patronymic, $surname]);
        $query = 'SELECT LAST_INSERT_ID() as lastInsertIdProfile';
        $this->db->query($query)->fetch(PDO::FETCH_ASSOC)['lastInsertIdProfile'];
    }

    public function deleteFullyCompletedProfile($profileId)
    {
        try {
            $this->db->beginTransaction();

            $this->email->deleteEmails($profileId);

            $this->telephone->deleteTelephones($profileId);

            $this->deleteProfile($profileId);

            $this->db->commit();
        } catch (Exception $exception) {
            $this->db->rollBack();
            throw $exception;
        }
    }

    public function deleteProfile($profileId)
    {
        $query = "DELETE FROM profiles WHERE id = $profileId";
        $this->db->query($query);
    }
}