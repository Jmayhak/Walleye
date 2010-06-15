<?php

/**
 * walleye.database.php
 *
 * Exception class to handle the Walleye_database class
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
 * @package Walleye
 */
class Walleye_database_exception extends Exception {
    public function __construct() {
        parent::__construct();
    }
}

/**
 * walleye.database.php
 *
 * This is the class that handles all connections to the database. It uses
 * PQP to print the queries to the screen if not in production.
 *
 * Uses MySQLi
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
 * @package Walleye
 */
class Walleye_database extends MySQLi {

    /**
     * Creates the Database object and sets the database connection info based on
     * the config file.
     *
     * @access public
     * @see includes/walleye.config.php
     */
    public function __construct() {
        $dbOptions = Walleye_config::getDbOptions();
        $engine = $dbOptions['ENGINE'];
        $server = $dbOptions['SERVER'];
        $user = $dbOptions['USER'];
        $password = $dbOptions['PASS'];
        $database = $dbOptions['DATABASE'];
        parent::__construct($server, $user, $password, $database);
    }

}

?>