<?php


class Validation
{
    private static $db;
    private static $data;

    public static function validate($data, $validatedFieldsArray, $errorMessages)
    {
        self::$db = Db::getConnection();
        self::$data = $data;
        Helper::startSession();

        foreach ($errorMessages as $attributeRuleString => $errorMessage) {
            $attributeRuleArray = explode('.', $attributeRuleString);
            $attribute = $attributeRuleArray[0];
            $rule = $attributeRuleArray[1];
            $errors[$attribute][$rule] = $errorMessage;
        }

        foreach ($validatedFieldsArray as $field => $rules) {
            $rulesArray = explode('|', $rules);

            foreach ($rulesArray as $rule) {
                preg_match('#([a-zA-Z_]+)#', $rule, $matches);
                $ruleName = $matches[1];

                switch ($ruleName) {
                    case 'required':

                        $passed = self::required($field);

                        if (!$passed) {
                            $_SESSION['errors'][$field] = $errors[$field]['required'];
                            break(2);
                        }
                        break;
                    case 'email':
                        $passed = self::email($field);

                        if (!$passed) {
                            $_SESSION['errors'][$field] = $errors[$field]['email'];
                            break(2);
                        }
                        break;
                    case 'between':
                        preg_match('#between:(\d+),(\d+)#', $rule, $matches);
                        $parameters = [$field, $matches[1], $matches[2]];
                        $passed = self::between(...$parameters);
                        if (!$passed) {
                            $_SESSION['errors'][$field] = $errors[$field]['between'];
                            break(2);
                        }
                        break;
                    case 'unique':
                        preg_match('#unique:([a-zA-Z]+),?([a-zA-Z]+)?,?(\d+)?,?([a-zA-Z_]+)?#', $rule, $matches);
                        $passed = self::unique($field, $matches[1], $matches[2] ?? null, $matches[3] ?? null, $matches[4] ?? null);

                        if (!$passed[0]) {
                            $_SESSION['errors'][$field] = str_replace(':value', $passed[1], $errors[$field]['unique']);
                            break(2);
                        }
                        break;
                    case 'unique_array_values':
                        $passed = self::uniqueArrayValues($field);

                        if (!$passed) {
                            $_SESSION['errors'][$field] = $errors[$field]['unique_array_values'];
                            break(2);
                        }
                        break;
                    case 'telephones_format':
                        $passed = self::telephonesFormat($field);

                        if (!$passed[0]) {
                            $_SESSION['errors'][$field] = str_replace(':value', $passed[1], $errors[$field]['telephones_format']);
                            break(2);
                        }
                        break;
                }
            }
        }

        if (isset($_SESSION['errors'])) {
            header("Location: {$_SERVER['HTTP_REFERER']}");
            die();
        }

        return true;
    }

    private static function required($fieldName)
    {
        if (is_array(self::$data[$fieldName]) and count(self::$data[$fieldName])) {
            return self::arrayRequired($fieldName);
        }

        if (self::$data[$fieldName] !== '') {
            return true;
        }

        return false;
    }

    private static function arrayRequired($fieldName) {
        foreach (self::$data[$fieldName] as $value) {
            if ($value === '') {
                return false;
            }
        }
        return true;
    }

    private static function email($fieldName)
    {
        if (!empty(self::$data[$fieldName])) {
            $fieldValue = self::$data[$fieldName];
            if (is_array($fieldValue)) {
                return self::arrayEmail($fieldName);
            }

            if (filter_var(self::$data[$fieldName], FILTER_VALIDATE_EMAIL)) {
                return true;
            }
        }

        return false;
    }

    private static function arrayEmail($fieldName) {

        foreach (self::$data[$fieldName] as $value) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }
        return true;
    }

    private static function between($fieldName, $min, $max)
    {
        if (isset(self::$data[$fieldName])) {

            $fieldValue = self::$data[$fieldName];
            if (is_array($fieldValue)) {
                return self::arrayBetween($fieldName, $min, $max);
            }

            if (isset($fieldValue) and is_numeric($fieldValue) and $fieldValue >= $min and $fieldValue <= $max) {

                return true;
            }
        }

        return false;
    }

    private static function arrayBetween($fieldName, $min, $max) {
        foreach (self::$data[$fieldName] as $value) {
            if (empty($value) and !is_numeric($value) and $value < $min and $value > $max) {
                return false;
            }
        }
        return true;
    }

    private static function unique($fieldName, $tableName, $columnName = null, $ignoreId = null, $ignoreFieldName = null)
    {
        if (is_array(self::$data[$fieldName])) {
            return self::arrayUnique($fieldName, $tableName, $columnName, $ignoreId, $ignoreFieldName);
        }

        $columnForFind = $columnName ?? $fieldName;


        $query = "SELECT $columnForFind FROM $tableName WHERE $columnForFind = ?";
        if (isset($ignoreId) and isset($ignoreFieldName)) {
            $query .= " and $ignoreFieldName != $ignoreId";
        }

        $stmt = self::$db->prepare($query);
        $result = $stmt->execute([self::$data[$fieldName]]);

        if (!$stmt->fetch()) {
            return [true];
        }

        return [false, self::$data[$fieldName]];
    }

    private static function arrayUnique($fieldName, $tableName, $columnName = null, $ignoreId = null, $ignoreFieldName = null)
    {
        $columnForFind = $columnName ?? $fieldName;


        foreach (self::$data[$fieldName] as $value) {
            $query = "SELECT $columnForFind FROM $tableName WHERE $columnForFind = ?";
            if (isset($ignoreId) and isset($ignoreFieldName)) {
                $query .= " and $ignoreFieldName != $ignoreId";
            }

            $stmt = self::$db->prepare($query);
            $stmt->execute([$value]);

            if ($stmt->fetch()) {
                return [false, $value];
            }
        }

        return [true];
    }

    private static function uniqueArrayValues($fieldName)
    {
        if (is_array(self::$data[$fieldName])) {
            if (count(array_unique(self::$data[$fieldName])) == count(self::$data[$fieldName])) {
                return true;
            }

            return false;
        }

        return true;
    }

    private static function telephonesFormat($fieldName)
    {
        foreach (self::$data[$fieldName] as $number => $telephone) {
            if (!preg_match('#^8\d{10}$#', $telephone)) {
                return [false, $telephone];
            }

            return [true];
        }
    }
}