<?php


class Validation
{
    private $errors;

    public function isValid($value, $validators, $fieldName)
    {
        foreach ($validators as $validator) {

            if (isset($this->errors[$fieldName])) {
                return true;
            }

            if (!$validator->isValid($value)) {
                $this->errors[$fieldName] = $validator->getError();
            }
        }

        return $this->hasErrors();
    }

    private function hasErrors()
    {
        return !empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}