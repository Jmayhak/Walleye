<?php

/**
 * walleye.config.php
 *
 * Set all config settings in this class.
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.8
 * @package Walleye
 */
class Walleye_config {

    /**
     * Returns the Base directory for this application, if the app is in production
     * mode and if the app is in local mode
     * @return array
     */
    public static function getAppOptions() {
        return array(
                   // the location of this application from the server root. end with a '/'
                   'BASE' => '',
                   // end the domain with a '/'
                   'DOMAIN' => '',
                   // if in production mode, no php warning/errors will be shown
                   'PRODUCTION' => false,
                   'LOG_ERRORS' => true,
                   // the location of the log file in relation to BASE
                   'LOG_FILE' => 'logs/app.log',
                   // Enter the expiration time in days
                   'REG_KEY_EXPIRE_TIME' => '7', // not implemented
                   // The length a session lasts in code in days (php.ini controls the session variable)
                   'SESSION_KEY_EXPIRE_TIME' => '1'
               );
    }
    
    /**
     * Returns the necessary information to make a connection to a database
     * @return array
     */
    public static function getDbOptions() {
        // !todo move to a ini file?
        return array(
                   'ENGINE' => '',
                   'SERVER' => '',
                   'USER' => '',
                   'PASS' => '',
                   'DATABASE' => ''
               );
    }
    
    /**
     * Returns the basic routes this application will follow and which controller Walleye will
     * pass control to
     * @return array
     */
    public static function getRoutes() {
        return array(
                   '/^(\/user)/' => 'User',
                   '/^(\/api)/' => 'Api',
                   'default' => 'Site'
               );
    }
}

$appOptions = Walleye_config::getAppOptions();

// PRODUCTION
if ($appOptions['PRODUCTION']) {
    // Turn off all error reporting
    ini_set("display_errors", 0);
}
// DEVELOPMENT
else {

}

?>