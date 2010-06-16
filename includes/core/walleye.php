<?php

require('walleye.config.php');
require('walleye.functions.php');
require('walleye.database.php');
require('walleye.model.php');
require('walleye.controller.php');
require('walleye.user.php');
require('libraries/pqp/pqp.php');

/**
 * walleye.php
 *
 * The base exception handler for Walleye
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
 * @package Walleye
 */
class Walleye_exception extends Exception {
    public function __construct() {
        parent::__construct();
    }
}

/**
 * walleye.php
 *
 * Handles the basic routing of URLs to their proper controller.
 *
 * @final
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
 * @package Walleye
 */
final class Walleye {

    /**
     * The singleton instance of Walleye
     * @var Walleye
     * @access private
     */
    private static $me;

    /**
     * Either the GET or POST data
     * @var array
     * @access private
     */
    private $data = array();

    /**
     * The full url
     * @var string
     * @access private
     */
    private $url;

    /**
     * The instance of PQP used for logging
     * @var PhpQuickProfiler
     * @access private
     */
    private $pqp;

    /**
     * Should be the only instance of the database so that pqp can track all db calls
     * @see PhpQuickProfiler
     * @var Walleye_database
     */
    private $db;
    
    /**
     * The base directory address given in the config array
     * @see core/walleye.config.php
     * @var string
     * @static
     * @access private
     */
    private static $server_base_dir;
    
    /**
     * The domain name of this app
     * @see core/walleye.config.php
     * @var string
     * @static
     * @access private
     */
    private static $domain;

    /**
     * All of the application options given in the config array
     * @see core/config/config.php
     * @var string
     * @access private
     */
    private $appOptions = array();

    /**
     * All of the database config options given in the database config array
     * @see core/config/database.php
     * @var string
     * @access private
     */
    private $dbOptions = array();

    /**
     * All of the application routes in the routes array
     * @see core/config/routes.php
     * @var string
     * @access private
     */
    private $routes = array();

    /**
     * Starts the session, instantiates the pqp object, the post or get data, and the action given in the url
     */
    private function __construct() {
        session_start();
        $this->pqp = new PhpQuickProfiler(PhpQuickProfiler::getMicroTime());
        $this->data = $this->getDataFromUrl($_SERVER["REQUEST_URI"]);
        $this->url = $_SERVER["REQUEST_URI"];
        $this->routes = Walleye_config::getRoutes();
        $this->db = new Walleye_database();
        $this->appOptions = Walleye_config::getAppOptions();
        if (isset($this->appOptions['BASE'])) {
            self::$server_base_dir = $this->appOptions['BASE'];
        }
        if (isset($this->appOptions['DOMAIN'])) {
            self::$domain = $this->appOptions['DOMAIN'];
        }
    }

    /**
     * Makes sure Walleye is handled as a singleton. This function will give you
     * the instance of Walleye.
     *
     * @return Walleye
     */
    public static function getInstance() {
        if (!self::$me) {
            self::$me = new Walleye();
        }
        return self::$me;
    }
    
    /**
     * Returns the instance of the database that should be used through the app in order
     * to use pqp to keep track of all database calls
     * @return Walleye_database
     */
    public function db() {
        return $this->db;
    }

    /**
     * Should be called directly after retrieving the Walleye object. This function performs the action
     * given in the url and shows pqp if not in production mode.
     *
     * @return void
     */
    public function run() {
        $this->route();
        /*if (!$this->appOptions['PRODUCTION']) {
            $this->pqp->display(Walleye_database::getInstance());
        }*/
        exit();
    }

    /**
     * The specific controller is selected based on the controller given in the URL.
     *
     * @see includes/core/config/routes.php
     * @return void
     */
    private function route() {
        foreach ($this->routes as $route => $controller) {
            if ($route == 'default') {
                if (class_exists($controller) && in_array(('doAction'), get_class_methods($controller))) {
                    $instance = new $controller($this->url, $this->data);
                    $instance->doAction();
                }
                break;
            }
            else {
                if (preg_match($route, $this->url)) {
                    if (class_exists($controller)) {
                        $instance = new $controller($this->url, $this->data);
                        $instance->doAction();
                    }
                    break;
                }
            }
        }

    }

    /**
     * Returns either the GET or POST data depending on the request type
     *
     * @return array
     */
    private function getDataFromUrl() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            return $_POST;
        }
        return $_GET;
    }

    /**
     * Gives the base directory for this application
     *
     * @return string
     */
    public static function getServerBaseDir() {
        return self::$server_base_dir;
    }
    
    /**
     * Gives the domain of this app
     * @return string
     */
    public static function getDomain() {
        return self::$domain;
    }

    /**
     * Checks if the browser is a mobile platform. Currently only checks for iphone.
     *
     * @return boolean
     */
    private function isMobile() {
        if (strstr($_SERVER['HTTP_USER_AGENT'], " AppleWebKit/") && strstr($_SERVER['HTTP_USER_AGENT'], " Mobile/")) {
            return true;
        }
        return false;
    }

    /**
     * Still waiting to see if there is a case were printing the Walleye object is necessary or useful
     *
     * @return string
     */
    public function __toString() {
        return '';
    }
}

?>
