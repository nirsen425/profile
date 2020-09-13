<?php


class UniqueValidator extends Validator
{
    private $db;
    private $tableName;
    private $columnForFind;
    private $ignoreId;
    private $ignoreFieldName;

    public function __construct($error, $tableName, $columnForFind, $ignoreId = null, $ignoreFieldName = null)
    {
        parent::__construct($error);

        $this->db = Db::getConnection();
        $this->tableName = $tableName;
        $this->columnForFind = $columnForFind;
        $this->ignoreId = $ignoreId ?? null;
        $this->ignoreFieldName = $ignoreFieldName ?? null;
    }

    public function isValid($value)
    {
        $query = "SELECT $this->columnForFind FROM $this->tableName WHERE $this->columnForFind = ?";
        if (isset($this->ignoreId) and isset($this->ignoreFieldName)) {
            $query .= " and $this->ignoreFieldName != $this->ignoreId";
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute([$value]);

        if (!$stmt->fetch()) {
            return true;
        }

        return false;
    }
}