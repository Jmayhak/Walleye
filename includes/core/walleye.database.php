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
            'DB_SERVER' => '127.0.0.1',
            'DB_USER' => 'admin',
            'DB_PASS' => 'teSpe7rabagArUnu',
            'DB_DATABASE' => 'fbc_development'
        );
        $database = isset($dbOptions['DB_DATABASE']) ? $dbOptions['DB_DATABASE'] : $this->dbOptions['DB_DATABASE'];
        $user = isset($dbOptions['DB_USER']) ? $dbOptions['DB_USER'] : $this->dbOptions['DB_USER'];
        $password = isset($dbOptions['DB_PASS']) ? $dbOptions['DB_PASS'] : $this->dbOptions['DB_PASS'];
        $server = isset($dbOptions['DB_SERVER']) ? $dbOptions['DB_SERVER'] : $this->dbOptions['DB_SERVER'];
        try {
            parent::__construct("mysql:host=$server;dbname=$database", $user, $password);
        }
        catch (PDOException $ex) {
            Console::logError($ex, $ex->getMessage());
            return null;
        }
    }

    /**
     * Overrides the PDO prepare function to keep track of the query for PQP. Also sets the fetch mode to
     * asscociative.
     *
     * @param  $query string
     * @return PDOStatement
     */
    public function prepare($query) {
        $start = $this->getTime();
        $stmt = parent::prepare($query);
        $this->query = $query;
        $this->logQuery($this->query, $start);
        $this->queryCount += 1;
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt;
    }

    /**
     * This is used to keep track of all db queries
     *
     * @access private
     * @param $sql string
     * @param $start string
     * @return void;
     */
    private function logQuery($sql, $start) {

        $query = array(
            'sql' => $sql,
            'time' => ($this->getTime() - $start) * 1000
        );
        array_push($this->queries, $query);
    }

    /**
     * @access private
     * @return string as a modified microtime
     */
    private function getTime() {

        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $start = $time;
        return $start;
    }

    /**
     * Takes the time and makes it readable
     * @param $time
     * @return string
     */
    public function getReadableTime($time) {

        $ret = $time;
        $formatter = 0;
        $formats = array('ms', 's', 'm');
        if ($time >= 1000 && $time < 60000) {
            $formatter = 1;
            $ret = ($time / 1000);
        }
        if ($time >= 60000) {
            $formatter = 2;
            $ret = ($time / 1000) / 60;
        }
        $ret = number_format($ret, 3, '.', '') . ' ' . $formats[$formatter];
        return $ret;
    }
}

?>