<?php


class View
{
    public static function render($fileName, $data = null)
    {
        $path = ROOT . '/views/' . $fileName . '.php';
        if (file_exists($path)) {
            ob_start();
            if (isset($data)) {
                extract($data);
            }
            $fileContent = require_once $path;
            return ob_get_clean();
        }
    }
}