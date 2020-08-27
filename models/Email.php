<?php


class Email
{
    private $db;

    public function __construct()
    {
        $this->db = Db::getConnection();
    }

    public function saveEmails($profileId, $emailsList, $mainEmailNumber)
    {
        $query = "INSERT INTO emails (title, main, profile_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        foreach ($emailsList as $number => $email) {
            $isMain = 0;
            if ($number  == $mainEmailNumber) {
                $isMain = 1;
            }
            $stmt->execute([$email, $isMain, $profileId]);
        }
    }

    public function getMainEmailTitleByProfileId($profileId)
    {
        $query = "SELECT title FROM emails WHERE profile_id = $profileId and main = 1";
        $stmt = $this->db->query($query);
        $emailTitle = $stmt->fetch()['title'];

        return $emailTitle;
    }

    public function getAllEmailsByProfileId($profileId)
    {
        $query = "SELECT id, title, main FROM emails WHERE profile_id = $profileId";
        $stmt = $this->db->query($query);
        $emails = $stmt->fetchAll();

        return $emails;
    }

    public function changeEmailTable($profileId, $emailsList, $mainEmailNumber)
    {
        $emailsByProfileId = $this->getAllEmailsByProfileId($profileId);

        $emailsTitle = array_map(function ($email) {
            return $email['title'];
        }, $emailsByProfileId);

        $emailsForDelete = array_diff($emailsTitle, $emailsList);
        $this->deleteEmailsByTitle($emailsForDelete);

        $emailsForUpdate = array_intersect($emailsList, $emailsTitle);
        $this->updateEmails($emailsForUpdate, $mainEmailNumber);


        $emailsForAdd = array_diff($emailsList, $emailsTitle);
        $this->saveEmails($profileId, $emailsForAdd, $mainEmailNumber);
    }

    public function deleteEmails($profileId)
    {
        $query = "DELETE FROM emails WHERE profile_id = $profileId";
        $this->db->query($query);
    }

    private function updateEmails($emailsList, $mainEmailNumber)
    {
        foreach ($emailsList as $number => $email) {
            $isMain = 0;

            if ($number  == $mainEmailNumber) {
                $isMain = 1;
            }

            $query = "UPDATE emails SET title = '$email', main = $isMain WHERE title = '$email'";
            $this->db->query($query);
        }
    }

    public function deleteEmailsByTitle($emailList)
    {
        foreach ($emailList as $emailForDelete) {
            $query = "DELETE FROM emails WHERE title = '$emailForDelete'";
            $this->db->query($query);
        }
    }
}