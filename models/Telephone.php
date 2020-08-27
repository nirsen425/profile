<?php


class Telephone
{
    private $db;

    public function __construct()
    {
        $this->db = Db::getConnection();
    }

    public function saveTelephones($profileId, $telephonesList, $telephoneTypesList, $telephoneMainNumber)
    {
        $db = Db::getConnection();

        $query = 'INSERT INTO telephones (title, main, telephone_type_id, profile_id) VALUES (?, ?, ?, ?)';
        $stmt = $db->prepare($query);
        for ($i = 0; $i < count($telephonesList); $i++) {
            $main = 0;
            if ($i  == $telephoneMainNumber) {
                $main = 1;
            }

            $stmt->execute([$telephonesList[$i], $main, $telephoneTypesList[$i], $profileId]);
        }
    }

    public function getMainTelephoneTitleByProfileId($profileId)
    {
        $query = "SELECT title FROM telephones WHERE profile_id = $profileId and main = 1";
        $stmt = $this->db->query($query);
        $telephoneTitle = $stmt->fetch()['title'];

        return $telephoneTitle;
    }

    public function getAllTelephonesByProfileId($profileId)
    {
        $query = "SELECT id, title, main, telephone_type_id FROM telephones WHERE profile_id = $profileId";
        $stmt = $this->db->query($query);
        $telephones = $stmt->fetchAll();

        return $telephones;
    }

    public function updateTelephones($profileId, $telephoneList, $telephoneTypeList, $telephoneMainNumber)
    {
        $db = Db::getConnection();

        $telephonesByProfileId = $this->getAllTelephonesByProfileId($profileId);

        $telephonesTitle = array_map(function ($email) {
            return $email['title'];
        }, $telephonesByProfileId);

        $telephonesForDelete = array_diff($telephonesTitle, $telephoneList);
        foreach ($telephonesForDelete as $telephoneForDelete) {
            $query = "DELETE FROM telephones WHERE title = '$telephoneForDelete'";
            $this->db->query($query);
        }

        $telephonesForUpdate = array_intersect($telephoneList, $telephonesTitle);

        foreach ($telephonesForUpdate as $number => $telephone) {
            $isMain = 0;

            if ($number  == $telephoneMainNumber) {
                $isMain = 1;
            }

            $query = "UPDATE telephones SET title = '$telephone', main = $isMain, telephone_type_id = $telephoneTypeList[$number]  WHERE title = '$telephone'";
            $this->db->query($query);
        }

        $telephonesForAdd = array_diff($telephoneList, $telephonesTitle);
        foreach ($telephonesForAdd as $number => $telephone) {
            $query = "INSERT INTO telephones (title, main, telephone_type_id, profile_id) VALUES (?, ?, ?, ?)";
            $stmt = $db->prepare($query);
            $isMain = 0;
            if ($number  == $telephoneMainNumber) {
                $isMain = 1;
            }
            $stmt->execute([$telephone, $isMain, $telephoneTypeList[$number], $profileId]);
        }
    }

    public function deleteTelephones($profileId)
    {
        $query = "DELETE FROM telephones WHERE profile_id = $profileId";
        $this->db->query($query);
    }
}