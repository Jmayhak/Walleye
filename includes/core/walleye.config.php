<?php

namespace Walleye;

/**
 * walleye.config.php
 *
 * Set all config settings in this class.
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 */
class Config
{
    const PRODUCTION = 'production';

    const DEVELOPMENT = 'development';

    const TESTING = 'testing';

    /**
     * Returns the Base directory for this application, if the app is in production
     * mode and if the app is in local mode
     * @return array
     */
    public static function getAppOptions()
    {
        return array(
            // the location of this application from the server root. end with a '/'
            'BASE' => '/srv/www/',
            // end the domain with a '/'
            'DOMAIN' => 'http://example.com/',
            // if in production, no php warning/errors will be shown
            // use testing for unit tests and functional tests
            // development should match production
            'ENVIRONMENT' => Config::TESTING,
            // errors are logged to the Logs table in the db
            'LOG_ERRORS' => true,
            // Enter the expiration time in days
            'REG_KEY_EXPIRE_TIME' => '7', // not implemented
            // The length a session lasts in code in days (php.ini controls the session variable)
            'SESSION_KEY_EXPIRE_TIME' => '1',
            'EMAIL_FROM' => ''
        );
    }

    /**
     * Returns the necessary information to make a connection to a database
     * @return array
     */
    public static function getDbOptions()
    {
        return array(
            // DEVELOPMENT
            'DEV_ENGINE' => 'mysql',
            'DEV_SERVER' => '',
            'DEV_USER' => '',
            'DEV_PASS' => '',
            'DEV_DATABASE' => '',
            // TESTING
            'TEST_ENGINE' => 'mysql',
            'TEST_SERVER' => '',
            'TEST_USER' => '',
            'TEST_PASS' => '',
            'TEST_DATABASE' => '',
            // PRODUCTION
            'PROD_ENGINE' => 'mysql',
            'PROD_SERVER' => '',
            'PROD_USER' => '',
            'PROD_PASS' => '',
            'PROD_DATABASE' => ''
        );
    }

    /**
     * Returns the basic routes this application will follow and which controller Walleye will
     * pass control to
     * @return array
     */
    public static function getRoutes()
    {
        // the key is a regexp and the value is the name of the controller class
        return array(
            '/^(\/user)/' => 'User',
            '/^(\/api)/' => 'Api',
            'default' => 'Site'
        );
    }
}
