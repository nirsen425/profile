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
        foreach ($telephonesList as $number => $telephone) {
            $query = "INSERT INTO telephones (title, main, telephone_type_id, profile_id) VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $isMain = 0;
            if ($number  == $telephoneMainNumber) {
                $isMain = 1;
            }
            $stmt->execute([$telephone, $isMain, $telephoneTypesList[$number], $profileId]);
        }
    }

    public function getMainTelephoneTitleByProfileId($profileId)
    {
        $query = "SELECT title FROM telephones WHERE profile_id = ? and main = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $profileId, PDO::PARAM_INT);
        $stmt->execute();
        $telephoneTitle = $stmt->fetch(PDO::FETCH_ASSOC)['title'];

        return $telephoneTitle;
    }

    public function getAllTelephonesByProfileId($profileId)
    {
        $query = "SELECT id, title, main, telephone_type_id FROM telephones WHERE profile_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $profileId, PDO::PARAM_INT);
        $stmt->execute();
        $telephones = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $telephones;
    }

    public function changeTelephoneTable($profileId, $telephoneList, $telephoneTypesList, $telephoneMainNumber)
    {
        $db = Db::getConnection();

        $telephonesByProfileId = $this->getAllTelephonesByProfileId($profileId);

        $telephonesTitle = array_map(function ($email) {
            return $email['title'];
        }, $telephonesByProfileId);

        $telephonesForDelete = array_diff($telephonesTitle, $telephoneList);
        $this->deleteTelephonesByTitle($telephonesForDelete);

        $telephonesForUpdate = array_intersect($telephoneList, $telephonesTitle);
        $this->updateTelephones($telephonesForUpdate, $telephoneTypesList, $telephoneMainNumber);

        $telephonesForAdd = array_diff($telephoneList, $telephonesTitle);
        $this->saveTelephones($profileId, $telephonesForAdd, $telephoneTypesList, $telephoneMainNumber);
    }

    public function deleteTelephones($profileId)
    {
        $query = "DELETE FROM telephones WHERE profile_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(1, $profileId);
        $stmt->execute();
    }

    public function deleteTelephonesByTitle($telephoneList)
    {
        $query = "DELETE FROM telephones WHERE title = ?";
        $stmt = $this->db->prepare($query);

        foreach ($telephoneList as $telephoneForDelete) {
            $stmt->bindValue(1, $telephoneForDelete, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    public function updateTelephones($telephoneList, $telephoneTypesList, $telephoneMainNumber)
    {
        $query = "UPDATE telephones SET main = ?, telephone_type_id = ? WHERE title = ?";
        $stmt = $this->db->prepare($query);
        foreach ($telephoneList as $number => $telephone) {
            $isMain = 0;

            if ($number  == $telephoneMainNumber) {
                $isMain = 1;
            }
            $stmt->execute([$isMain, $telephoneTypesList[$number], $telephone]);
        }
    }
}