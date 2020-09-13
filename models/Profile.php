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
        try {
            $this->db->beginTransaction();

            $lastInsertIdProfile = $this->saveProfile($data['name'],
                $data['patronymic'], $data['surname']);

            $this->email->saveEmails($lastInsertIdProfile, $data['email'], $data['main-email']);

            $this->telephone->saveTelephones($lastInsertIdProfile, $data['telephone'],
                $data['telephone-type'], $data['main-telephone']);

            $this->db->commit();
        } catch (Exception $exception) {
            $this->db->rollBack();
            throw $exception;
        }

        return true;
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

        if (!$profile) {
            return false;
        }

        return $profile;
    }

    public function updateFullyCompletedProfile($profileId, $data)
    {
        try {
            $this->db->beginTransaction();

            $this->updateProfile($profileId, $data['name'],
                $data['patronymic'], $data['surname']);

            $this->email->changeEmailTable($profileId, $data['email'], $data['main-email']);

            $this->telephone->changeTelephoneTable($profileId, $data['telephone'],
                $data['telephone-type'], $data['main-telephone']);

            $this->db->commit();

            return true;
        } catch (Exception $exception) {
            $this->db->rollBack();
            throw $exception;
        }

        return true;
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