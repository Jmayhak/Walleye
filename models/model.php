<?php
require(DB_CONFIG);
require(DB_SQL_CORE);
require(DB_SQL_WRAPPER);

/**
 * This is the class that handles all connections to the database. It uses
 * PQP to print the results to the screen if not in production.
 */
class Model extends ezSQL_mysql {

    public $queryCount = 0;
    public $queries = array();
    private static $instance;
    private $database;
    private $user;
    private $password;
    private $server;

    /**
     * Creates the Model object and sets the database connection info based on
     * the config file
     *
     * @see config/config.php
     */
    private function __construct() {
        $this->database = DB_DATABASE_DEVELOPMENT;
        $this->user = DB_USER;
        $this->password = DB_PASS;
        $this->server = DB_SERVER;

        if(LOCAL == "true") {
            $this->server = "127.0.0.1";
            $this->user = "root";
            $this->password = "";
        }
        $this->connect($this->user, $this->password, $this->server);
        $this->select($this->database);
    }

    /**
     * Makes sure Model is handled as a singleton. This function will give you
     * an instance (the only instance) of Model.
     *
     * @return Model
     */
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Model();
        }
        return self::$instance;
    }
    /**
     * Use this function for inserts and updates
     * Use db_query instead of ezSQL_mysql's query function because this method logs the sql statement in pqp
     * @property $query string
     * @return bool
     */
    public function query($query) {

        $start = $this->getTime();
        $result = parent::query($query);
        $this->queryCount += 1;
        $this->logQuery($query, $start);
        return $result;
    }

    /**
     * Use this function to select a list of results
     * Use db_get_results instead of ezSQL_mysql's get_results function
     * @property $query string
     * @return array
     */
    public function get_results($query) {

        $result = parent::get_results($query, ARRAY_A);
        return $result;
    }
    /**
     * Use this function to select a single variable
     * @property $query string
     * @return string
     */
    public function get_var($query) {

        $result = parent::get_var($query);
        return $result;
    }
    /**
     * Use this function to select a single row
     * @property $query string
     * @return array
     */
    public function get_row($query) {

        $result = parent::get_row($query, ARRAY_A);
        return $result;
    }
    /**
     * This is used to keep track of all db queries
     * @property $sql string
     * @property $start string
     */
    function logQuery($sql, $start) {

        $query = array(
                'sql' => $sql,
                'time' => ($this->getTime() - $start)*1000
        );
        array_push($this->queries, $query);
    }
    /**
     * @return string as a modified microtime
     */
    function getTime() {

        $time = microtime();
        $time = explode(' ', $time);
        $time = $time[1] + $time[0];
        $start = $time;
        return $start;
    }
    /**
     * Takes the time and makes it readable
     * @property $time
     */
    public function getReadableTime($time) {

        $ret = $time;
        $formatter = 0;
        $formats = array('ms', 's', 'm');
        if($time >= 1000 && $time < 60000) {
            $formatter = 1;
            $ret = ($time / 1000);
        }
        if($time >= 60000) {
            $formatter = 2;
            $ret = ($time / 1000) / 60;
        }
        $ret = number_format($ret,3,'.','') . ' ' . $formats[$formatter];
        return $ret;
    }
    /**
     * @return string a nice way of seeing the database info for this model
     */
    public function toString() {

        return "Server: '$this->server' " .
                "Database: '$this->database' " .
                "User: '$this->user' " .
                "Password: '$this->password'";
    }

}

require(USER_MODEL);
require(API_MODEL);
require(ADMIN_MODEL);
require(EXCEPTION_MODEL);

?>