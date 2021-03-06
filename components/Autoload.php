<?php

spl_autoload_register('loadClasses');

function loadClasses($className)
{
    $arrayPaths = [
        '/models/',
        '/components/',
        '/components/validators/',
        '/components/FormValidators/',
        '/controllers/',

    ];

    foreach ($arrayPaths as $path) {
        $path = ROOT . $path . $className . '.php';
        if (is_file($path)) {
            require_once $path;
        }
    }
}