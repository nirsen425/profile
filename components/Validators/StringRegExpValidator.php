<?php


class StringRegExpValidator extends Validator
{
    private $regExp;

    public function __construct($error, $regExp)
    {
        parent::__construct($error);

        $this->regExp = $regExp;
    }

    public function isValid($value)
    {
        if (!preg_match($this->regExp, $value)) {
            return false;
        }

        return true;
    }
}