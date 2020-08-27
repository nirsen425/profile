<?php


class Helper
{
    public static function startSession() {
        if (session_id()) return true;
        else return session_start();
    }

    public static function getClearData($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = Helper::getClearData($value);
            } else {
                $data[$key] = htmlspecialchars(trim($value));
            }
        }

        return $data;
    }
}