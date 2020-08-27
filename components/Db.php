<?php


class Db
{
    private static $db;

    public static function getConnection()
    {
        if (!empty(self::$db)) {
            return self::$db;
        }

        $paramsPath = ROOT . '/config/db_params.php';
        $params = require_once $paramsPath;

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $db = new PDO($dsn, $params['user'], $params['password']);
        $db->exec("set names utf8");

        self::$db = $db;

        return $db;
    }
}