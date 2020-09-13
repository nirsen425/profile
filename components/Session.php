<?php


class Session
{
    public static function saveFormDataInSession($formName)
    {
        Helper::startSession();
        foreach ($_POST as $key => $value) {
            $_SESSION[$formName][$key] = $value;
        }
    }

    public static function deleteFormDataInSession($formName)
    {
        Helper::startSession();
        unset($_SESSION[$formName]);
    }

    public static function writeArrayInSessionByKey($array, $key)
    {
        Helper::startSession();
        foreach ($array as $k => $v) {
            $_SESSION[$key][$k] = $v;
        }
    }
}