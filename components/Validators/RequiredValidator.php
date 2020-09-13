<?php

/**
 * Класс валидации на существование значения
 */
class RequiredValidator extends Validator
{
    /**
     * Проверяет на существование значения отличного от null и ''
     *
     * @param $value проверяемое значение
     * @param $message сообщение ошибки
     * @return bool
     */
    public function isValid($value)
    {
        if (isset($value) and $value !== '') {
            return true;
        }

        return false;
    }
}