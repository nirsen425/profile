<?php


class UniqueArrayValuesValidator extends Validator
{
    public function isValid($value)
    {
        if (count(array_unique($value)) == count($value)) {
            return true;
        }

        return false;
    }
}