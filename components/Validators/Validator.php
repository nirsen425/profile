<?php


abstract class Validator
{
    private $error;

    public function __construct($error)
    {
        $this->error = $error;
    }

    abstract public function isValid($value);

    public function getError()
    {
        return $this->error;
    }
}