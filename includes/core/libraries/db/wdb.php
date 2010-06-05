<?php

/**
 * This is the class that handles all connections to the database. It uses
 * PQP to print the queries to the screen if not in production.
 *
 * Uses PDO
 *
 */
class wDb extends PDO {

    private static $instance;
    private $dbOptions;

    public $queryCount = 0;
    public $queries = array();
    private $query;

    /**
     * Creates the wDb object and sets the database connection info based on
     * the config file. The dbOptions can ONLY be sent on the initialization of
     * the wDb object (the first time getInstance() is called). This is done in the
     * Walleye class when the setDbOptions() function is called.
     *
     * @see includes/config/db.php
     * @param array $dbOptions the server, username, password, and database to connect to
     */
    private function wDb($dbOptions = array()) {
        if (!empty($dbOptions)) {
            $this->dbOptions = $dbOptions;
            $database = $dbOptions['DB_DATABASE'];
            $user = $dbOptions['DB_USER'];
            $password = $dbOptions['DB_PASS'];
            $server = $dbOptions['DB_SERVER'];
            try {
                parent::__construct("mysql:host=$server;dbname=$database", $user, $password);
            }
            catch (PDOException $ex) {
                Console::logError($ex, $ex->getMessage());
            }
        }
    }

    /**
     * Makes sure wDb is handled as a singleton. This function will give you
     * an instance (the only instance) of wDb.
     *
     * @return wDb
     */
    public static function getInstance($dbOptions = array()) {
        if (!self::$instance) {
            self::$instance = new wDb($dbOptions);
        }
        return self::$instance;
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
        if ($stmt) {
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
        }
        return $stmt;
    }

    /**
     * This is used to keep track of all db queries
     *
     * @param $sql string
     * @param $start string
     */
    private function logQuery($sql, $start) {

        $query = array(
            'sql' => $sql,
            'time' => ($this->getTime() - $start) * 1000
        );
        array_push($this->queries, $query);
    }

    /**
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