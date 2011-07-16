<?php

/**
 * Attempts to require a class at runtime if it has not been loaded yet.
 *
 * Classes in subdirectories must follow a PEAR style naming convention for classes to be autoloaded into walleye.
 * For example: controllers/folder/file.php , all classes in file.php must be FOLDER_classname.
 * This is not case-sensitive.
 *
 * If your class name has underscores in it, they will be converted to a slash for file resolution.
 *
 * @param string $class_name
 * @return void
 */
function __autoload($class_name)
{
    if (strpos($class_name, '\\') !== FALSE) {
        $class_name_array = explode('\\', $class_name);
        $class_name = array_pop($class_name_array);
    }
    $class_name = str_replace('_', '/', $class_name).'.php';
    if (file_exists(\Walleye\Walleye::getInstance()->getServerBaseDir() . 'includes/app/controllers/' . strtolower($class_name))) {
        require(\Walleye\Walleye::getInstance()->getServerBaseDir() . 'includes/app/controllers/' . strtolower($class_name));
    }
    if (file_exists(\Walleye\Walleye::getInstance()->getServerBaseDir() . 'includes/app/models/' . strtolower($class_name))) {
        require(\Walleye\Walleye::getInstance()->getServerBaseDir() . 'includes/app/models/' . strtolower($class_name));
    }
}

/**
 * Takes a string and returns a 256bit string hash whirlpool style
 *
 * @final
 * @access protected
 * @param string $data
 * @return string|null
 */
function hash_data($data)
{
    if (is_string($data) == false) {
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
 * Will return a string of an array in a decent fashion. Used mostly for logging.
 *
 * THIS FUNCTION WILL PRINT TO THE BROWSER
 *
 * @param array $array
 * @return string
 */
function print_object($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

/**
 * Pass a string and this function will return the amount of days since then
 *
 * @param string $when should be a TIMESTAMP yyyy-mm-dd
 * @return int the number of days
 */
function daysFromNow($when)
{
    $when = explode(' ', $when);
    return floor((time() - strtotime($when[0])) / (60 * 60 * 24));
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

/**
 * Implodes an array full of arrays based on the passed key that is in each element of the array
 * by the passed delimiter
 *
 * implode_key(',', array(array('title'=>'jonathan'), array('title'=>'justin')), 'title')
 * // will return jonathan,justin
 *
 * @param string $delimiter
 * @param array $arrays
 * @param string $key
 * @return string
 */
function implode_by_key($delimiter, $arrays, $key)
{
    $new_array = array();
    foreach ($arrays as $array) {
        if (isset($array[$key]) == true) {
            $new_array[] = $array[$key];
        }
    }
    return implode($delimiter, $new_array);
}

/* End of file */
