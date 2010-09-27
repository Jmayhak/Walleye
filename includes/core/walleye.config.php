<?php

/**
 * walleye.config.php
 *
 * Set all config settings in this class.
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
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
                   // end the domain with a '/' and MUST start with http://
                   'DOMAIN' => '',
                   // if in production mode, no php warning/errors will be shown
                   'PRODUCTION' => false,
                   'LOG_ERRORS' => true,
                   // Enter the expiration time in days
                   'REG_KEY_EXPIRE_TIME' => '7', // not implemented
                   // The length a session lasts in code in days (php.ini controls the session variable)
                   'SESSION_KEY_EXPIRE_TIME' => '1',
				   // leave blank to not forward anywhere. only checks for iphone
				   'IF_MOBILE_REDIRECT' => ''
               );
    }
    
    /**
     * Returns the necessary information to make a connection to a database
     * @return array
     */
    public static function getDbOptions() {
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
	    // the key is a regexp and the value is the name of the controller class
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
    ini_set('display_errors', 0);
}
// DEVELOPMENT
else {
    ini_set('display_errors', 1)
}
