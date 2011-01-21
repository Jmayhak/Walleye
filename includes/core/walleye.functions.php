<?php

namespace Walleye;

/**
 * Attempts to require a class at runtime if it has not been loaded yet.
 *
 * If your class name has underscores in it, they will be converted to periods when searching for the file name.
 *
 * @param string $class_name
 * @return void
 */
function __autoload($class_name)
{
    $class_name = str_replace('_', '.', $class_name);
    $class_name_array = explode('\\', $class_name);
    $class_name = array_pop($class_name_array);
    if (file_exists(Walleye::getServerBaseDir() . 'includes/app/controllers/' . strtolower($class_name) . '.php')) {
        require(Walleye::getServerBaseDir() . 'includes/app/controllers/' . strtolower($class_name) . '.php');
    }
    if (file_exists(Walleye::getServerBaseDir() . 'includes/app/models/' . strtolower($class_name) . '.php')) {
        require(Walleye::getServerBaseDir() . 'includes/app/models/' . strtolower($class_name) . '.php');
    }
}

/**
 * Takes a string and returns a 256bit string hash whirlpool style
 *
 * @final
 * @access protected
 * @param array $data
 * @return string|null
 */
function hash_data($data)
{
    if (!is_string($data)) {
        return null;
    }
    return hash('whirlpool', $data);
}

/**
 * Pass the string to encode and the secret key as a salt
 * @see decrypt
 * @param string $string
 * @param string $key
 * @return string
 */
function encrypt($string, $key)
{
    $result = '';
    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) + ord($keychar));
        $result .= $char;
    }

    return base64_encode($result);
}

/**
 * Pass the encrypted string and the secret key to decrypt
 * @see encrypt
 * @param string $string
 * @param string $key
 * @return string
 */
function decrypt($string, $key)
{
    $result = '';
    $string = base64_decode($string);

    for ($i = 0; $i < strlen($string); $i++) {
        $char = substr($string, $i, 1);
        $keychar = substr($key, ($i % strlen($key)) - 1, 1);
        $char = chr(ord($char) - ord($keychar));
        $result .= $char;
    }

    return $result;
}

/**
 * Will return a string of  an array in a decent fashion. Used mostly for logging
 *
 * @param array $array
 * @return string
 */
function print_array($array)
{
    if (is_null($array)) {
        return '';
    }
    $returnString = '';
    foreach ($array as $key => $value) {
        if (is_array($key)) {
            $returnString .= print_array($key);
        }
        $returnString .= $key . ' - ' . $value . ' ';
    }
    return $returnString;
}

/**
 * Pass a string and this function will return the amount of days since then
 *
 * @param string $when should be a TIMESTAMP yyyy-mm-dd
 * @return int the number of days
 */
function daysFromNow($when)
{
    return floor((time() - strtotime($when)) / (60 * 60 * 24));
}

/**
 * Creates a url slug from a string
 * @param string $string
 * @return string
 */
function slugify($string)
{
    return strtolower(trim(preg_replace(array('~[^0-9a-z]~i', '~-+~'), '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'))), '-'));
}
