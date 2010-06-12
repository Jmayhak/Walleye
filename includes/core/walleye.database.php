<?php

/**
 * walleye.database.php
 *
 * This is the class that handles all connections to the database. It uses
 * PQP to print the queries to the screen if not in production.
 *
 * Uses PDO
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
 * @package Walleye
 */
class Walleye_database extends PDO {

    /**
     * The only instance of Walleye_database allowed
     * @var Walleye_database
     * @access private
     * @static
     */
    private static $me;

    /**
     * The information necessary to connect to the database
     * @var array
     * @access private
     */
    private $dbOptions = array();

    /**
     * The total amount of queries performed
     * @var int
     * @access public
     */
    public $queryCount = 0;

    /**
     * The queries performed
     * @var array
     * @access public
     */
    public $queries = array();

    /**
     * The most current query
     * @var string
     * @access private
     */
    private $query;

    /**
     * Creates the Model object and sets the database connection info based on
     * the config file. The dbOptions can ONLY be sent on the initialization of
     * the wDb object (the first time getInstance() is called). This is done in the
     * Walleye class when the setDbOptions() function is called.
     *
     * @access private
     * @see includes/config/database.php
     * @param array $dbOptions
     * @return Walleye_database|null
     */
    public function __construct() {
        $dbOptions = array(
            'ENGINE' => 'mysql',
            'SERVER' => '127.0.0.1',
            'USER' => 'admin',
            'PASS' => 'teSpe7rabagArUnu',
            'DATABASE' => 'fbc_development'
        );
        /*$database = isset($dbOptions['DB_DATABASE']) ? $dbOptions['DB_DATABASE'] : $this->dbOptions['DB_DATABASE'];
        $user = isset($dbOptions['DB_USER']) ? $dbOptions['DB_USER'] : $this->dbOptions['DB_USER'];
        $password = isset($dbOptions['DB_PASS']) ? $dbOptions['DB_PASS'] : $this->dbOptions['DB_PASS'];
        $server = isset($dbOptions['DB_SERVER']) ? $dbOptions['DB_SERVER'] : $this->dbOptions['DB_SERVER'];*/
        $server = $dbOptions['SERVER'];
        $user = $dbOptions['USER'];
        $password = $dbOptions['PASS'];
        $database = $dbOptions['DATABASE'];
        parent::__construct("mysql:host=$server;dbname=$database", $user, $password);
    }

}

?>