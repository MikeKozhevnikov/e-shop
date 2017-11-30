<?php

/**
 * __autoload function used for autoinclude base classes
 */
function __autoload($class_name)
{
    // folders array, that containts classes
    $array_paths = array(
        '/models/',
        '/components/',
        '/controllers/',
    );

    foreach ($array_paths as $path) {

        $path = ROOT . '/app' . $path . $class_name . '.php';

        // If file exists - include it
        if (is_file($path)) {
            include_once $path;
        }
    }
}