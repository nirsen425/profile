<?php


class TelephoneType
{
    private $db;

    public function __construct()
    {
        $this->db = Db::getConnection();
    }

    public function getTitleByTelephoneTypeId($telephoneTypeId)
    {
        $query = "SELECT title FROM telephone_types WHERE id = $telephoneTypeId";
        $stmt = $this->db->query($query);
        $title = $stmt->fetch()['title'];

        return $title;
    }
}