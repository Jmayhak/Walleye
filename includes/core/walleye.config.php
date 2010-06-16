<?php

/**
 * walleye.config.php
 *
 * Set all config settings in the class.
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
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
                   'BASE' => '/var/www/vhosts/veschile.com/subdomains/walleye/',
                   'DOMAIN' => 'http://jon.veschile.com/',
                   'PRODUCTION' => false,
                   'LOCAL' => false
               );
    }
    
    /**
     * Returns the necessary information to make a connection to a database
     * @return array
     */
    public static function getDbOptions() {
        // !todo move to a ini file?
        return array(
                   'ENGINE' => 'mysql',
                   'SERVER' => '127.0.0.1',
                   'USER' => 'admin',
                   'PASS' => 'teSpe7rabagArUnu',
                   'DATABASE' => 'fbc_development'
               );
    }
    
    /**
     * Returns the basic routes this application will follow. Which controller Walleye will
     * pass control to.
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

?>