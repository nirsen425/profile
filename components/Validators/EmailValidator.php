<?php


class EmailValidator extends Validator
{
    public function isValid($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return false;
    }
}