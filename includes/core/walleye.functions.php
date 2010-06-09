<?php

/**
 * Attempts to require a class at runtime if it has not been loaded yet.
 *
 * @param string $class_name
 * @return void
 */
function __autoload($class_name) {
    $class_name = str_replace('_', '.', $class_name);
    if (file_exists(Walleye::getServerBaseDir() . 'includes/app/controllers/' . strtolower($class_name) . '.php')) {
        require(Walleye::getServerBaseDir() . 'includes/app/controllers/' . strtolower($class_name) . '.php');
    }
    if (file_exists(Walleye::getServerBaseDir() . 'includes/app/models/' . strtolower($class_name) . '.php')) {
        require(Walleye::getServerBaseDir() . 'includes/app/models/' . strtolower($class_name) . '.php');
    }
}


?>