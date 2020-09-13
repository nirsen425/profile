<?php


class RangeValidator extends Validator
{
    private $min;
    private $max;

    public function __construct($error, $min, $max)
    {
        parent::__construct($error);

        $this->min = $min;
        $this->max = $max;
    }

    public function isValid($value)
    {
        if (isset($value) and is_numeric($value) and $value >= $this->min and $value <= $this->max) {

            return true;
        }
    }
}