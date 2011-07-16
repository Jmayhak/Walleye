<?php

namespace Walleye;

require('walleye.console.php');
require('walleye.functions.php');
require('walleye.database.php');
require('walleye.model.php');
require('walleye.controller.php');
require('walleye.user.php');
require('walleye.email.php');
require('walleye.table.php');

/**
 * walleye.php
 *
 * Handles the basic routing of URLs to their proper controller.
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @package Walleye
 */
class Walleye
{

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
     * The base directory address given in the config array
     *
     * @var string
     * @access private
     */
    private $serverBaseDir;

    /**
     * The domain name of this app
     *
     * @var string
     * @access private
     */
    private $domain;

    /**
     * The environment the application is running in
     * @var string
     * @access private
     */
    private $environment;

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
     * The time this application started in microseconds
     *
     * @var string
     * @access private
     */
    private $startTime;

    /**
     * The time this application finished in microseconds
     *
     * @var string
     * @access private
     */
    private $endTime;

    /**
     * The amount of time it took for this application to complete in a readable format
     *
     * @var string
     * @access private
     */
    private $executionTime;

    /**
     * All of the application routes in the routes array
     * @see core/config/routes.php
     * @var string
     * @access private
     */
    private $routes = array();


    /**
     * The route that is selected from the routes array
     *
     * @var string
     * @access private
     */
    private $route = array();


    /**
     * The handler called by the application
     *
     * @var array
     * @access private
     */
    private $handler = array();

    /**
     * The logs the app created
     *
     * @var array
     * @access private
     */
    private $logs = array();

    /**
     * The api key from google for loading jquery and jquery ui
     *
     * @var string
     * @access private
     */
    private $google_api_key = '';

    /**
     * The user id to use for unit testing
     */
    private $testingUserId = 0;

    /**
     * The queries that the app has logged
     *
     * @var array
     * @access private
     */
    private $queries = array();

    /**
     * The peak memory used on this page load
     *
     * @var string
     */
    private $peak_memory_usage;

    const PRODUCTION = 'production';

    const DEVELOPMENT = 'development';

    const TESTING = 'testing';

    /**
     * Starts the session, stores the post or get data, and the path given in the url
     *
     * @param array $appOptions
     * @param array $routes
     * @param array $dbOptions
     */
    private function __construct($appOptions, $routes, $dbOptions = array())
    {
        $this->startTime = EXECUTION_TIME_START;
        $this->routes = (empty($routes) == false) ? $routes
                : array('default' => '\App\Controllers\Site');
        $this->serverBaseDir = realpath(dirname(__FILE__) . '/../../') . '/';

        $this->appOptions = array(
            'ENVIRONMENT' => (isset($appOptions['ENVIRONMENT']) == true)
                    ? $appOptions['ENVIRONMENT'] : \Walleye\Walleye::PRODUCTION,
            'LOG_ERRORS' => (isset($appOptions['LOG_ERRORS']) == true)
                    ? $appOptions['LOG_ERRORS'] : true,
            'REG_KEY_EXPIRE_TIME' => (isset($appOptions['REG_KEY_EXPIRE_TIME']) == true)
                    ? $appOptions['REG_KEY_EXPIRE_TIME'] : '7',
            'SESSION_KEY_EXPIRE_TIME' => (isset($appOptions['SESSION_KEY_EXPIRE_TIME']) == true)
                    ? $appOptions['SESSION_KEY_EXPIRE_TIME'] : '1',
            'EMAIL_FROM' => (isset($appOptions['EMAIL_FROM']) == true)
                    ? $appOptions['EMAIL_FROM'] : 'no-reply',
            'PRINT_APP_INFO_ON_LOAD' => (isset($appOptions['PRINT_APP_INFO_ON_LOAD']) == true)
                    ? $appOptions['PRINT_APP_INFO_ON_LOAD'] : false,
            'VERSION' => (isset($appOptions['VERSION']) == true)
                    ? $appOptions['VERSION'] : '',
            'GOOGLE_API_KEY' => (isset($appOptions['GOOGLE_API_KEY']) == true)
                    ? $appOptions['GOOGLE_API_KEY'] : ''
        );

        $this->dbOptions = (empty($dbOptions) == false) ? array(
            'ENGINE' => (isset($dbOptions['ENGINE']) == true)
                    ? $dbOptions['ENGINE'] : 'mysql',
            'SERVER' => (isset($dbOptions['SERVER']) == true)
                    ? $dbOptions['SERVER'] : '127.0.0.1',
            'USER' => (isset($dbOptions['USER']) == true) ? $dbOptions['USER']
                    : '',
            'PASS' => (isset($dbOptions['PASS']) == true) ? $dbOptions['PASS']
                    : '',
            'DATABASE' => (isset($dbOptions['DATABASE']) == true)
                    ? $dbOptions['DATABASE'] : '',
            'PORT' => (isset($dbOptions['PORT']) == true) ? $dbOptions['PORT']
                    : '3306',
            'SALT' => (isset($dbOptions['SALT']) == true) ? $dbOptions['SALT']
                    : ''
        ) : array();

        // set environment specifics
        if ($this->appOptions['ENVIRONMENT'] == Walleye::PRODUCTION || $this->appOptions['ENVIRONMENT'] == Walleye::DEVELOPMENT) {
            $this->environment = ($this->appOptions['ENVIRONMENT'] == Walleye::PRODUCTION)
                    ? Walleye::PRODUCTION : Walleye::DEVELOPMENT;

            session_start();

            $this->appOptions['DOMAIN'] = 'http://' . $_SERVER['HTTP_HOST'] . '/';
            $this->data = $this->getDataFromUrl($_SERVER["REQUEST_URI"]);
            $this->domain = $this->appOptions['DOMAIN'];
            $this->google_api_key = $this->appOptions['GOOGLE_API_KEY'];

            $url_array = explode('?', $_SERVER["REQUEST_URI"]);
            $this->url = $url_array[0];
        }
        else {
            $this->environment = Walleye::TESTING;
            $this->testingUserId = $this->appOptions['TESTING_USER_ID'];
        }
    }

    /**
     * Print out the walleye object
     */
    public function __destruct()
    {
        if ($this->appOptions['PRINT_APP_INFO_ON_LOAD'] == true && $this->isProduction() == false && $this->isTesting() == false) {
            $this->logs = Console::getLogs();

            $this->queries = Console::getQueries();

            // log the time the application took to execute
            $start_time = $this->startTime;
            $end_time = $this->endTime = microtime();

            $start_time_array = explode(' ', $start_time);
            $end_time_array = explode(' ', $end_time);

            $time = (($end_time_array[1] + $end_time_array[0]) - ($start_time_array[1] + $start_time_array[0])) * 1000;

            // make time readable
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
            $this->executionTime = $ret = number_format($ret, 3, '.', '') . ' ' . $formats[$formatter];

            function convert($size)
            {
                $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
                return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
            }

            $this->peak_memory_usage = convert(memory_get_peak_usage(true));

            print_object($this);
        }

    }

    /**
     * Makes sure Walleye is handled as a singleton. This function will give you
     * the instance of Walleye.
     *
     * @param array $appOptions
     * @param array $routes
     * @param array $dbOptions
     * @return Walleye
     */
    public static function getInstance($appOptions = array(), $routes = array(), $dbOptions = array())
    {
        if (!self::$me) {
            self::$me = new Walleye($appOptions, $routes, $dbOptions);
        }
        return self::$me;
    }

    /**
     * Should be called directly after retrieving the Walleye object. This function performs the action
     * given in the url.
     *
     * @return void
     */
    public function run()
    {
        $this->route();
    }

    /**
     * The specific controller is selected based on the controller given in the URL.
     *
     * @see includes/core/config/routes.php
     * @return void
     */
    private function route()
    {
        foreach ($this->routes as $route => $controller) {
            if ($route == 'default') {
                if (class_exists($controller) && in_array(('doHandler'), get_class_methods($controller))) {
                    $this->route[$route] = $controller;
                    $instance = new $controller($this->url, $this->setLogsFromData($this->data));
                    $this->handler = $instance->doHandler();
                }
                break;
            }
            else {
                if (preg_match($route, $this->url)) {
                    if (class_exists($controller)) {
                        $this->route[$route] = $controller;
                        $instance = new $controller($this->url, $this->setLogsFromData($this->data));
                        $this->handler = $instance->doHandler();
                    }
                    break;
                }
            }
        }
    }

    /**
     * Any logs sent in the GET request will be removed from the data property and added through the Console class.
     * The returned array will be all original key/value pairs minus logs
     *
     * @param array $data
     * @return array
     */
    private function setLogsFromData($data)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            foreach ($data as $key => $value) {
                if ($key == 'logs' && is_array($value)) {
                    foreach ($value as $key => $log) {
                        if (isset($log['type']) && isset($log['message']) && isset($log['file']) && isset($log['line'])) {
                            switch ($log['type']) {
                                case Console::ALERT:
                                    Console::alert($log['message']);
                                    unset($data[$key]);
                                    break;
                                case Console::LOG:
                                    Console::log($log['message'], $log['file'], $log['line']);
                                    unset($data[$key]);
                                    break;
                                case Console::ERROR:
                                    Console::logError($log['message'], $log['file'], $log['line']);
                                    unset($data[$key]);
                                    break;
                                default:
                                    break;
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }

    /**
     * Returns either the GET or POST data depending on the request type
     *
     * @return array
     */
    private function getDataFromUrl()
    {
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
    public function getServerBaseDir()
    {
        return $this->serverBaseDir;
    }

    /**
     * Gives the domain of this app
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }


    /**
     * Returns the database options set for this environment.
     *
     * @access public
     * @return array
     */
    public function getDbOptions()
    {
        return $this->dbOptions;
    }


    /**
     * Returns the app options set for this environment
     *
     * @access public
     * @return array
     */
    public function getAppOptions()
    {
        return $this->appOptions;
    }

    /**
     * @return boolean
     */
    public function isProduction()
    {
        if ($this->environment == Walleye::PRODUCTION) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function isTesting()
    {
        if ($this->environment == Walleye::TESTING) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public function isDevelopment()
    {
        if ($this->environment == Walleye::DEVELOPMENT) {
            return true;
        }
        return false;
    }

    /**
     * @return void
     */
    public function setTestingUserId($id)
    {
        if ($this->isTesting()) {
            $this->testingUserId = $id;
        }
    }

    /**
     * @return int
     */
    public function getTestingUserId()
    {
        $testing_user_id = 0;
        if ($this->isTesting()) {
            $testing_user_id = $this->testingUserId;
        }
        return $testing_user_id;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        $appOptions = $this->getAppOptions();
        return $appOptions['VERSION'];
    }

    /**
     * @return string
     */
    public function getGoogleApiKey()
    {
        return $this->google_api_key;
    }

    /**
     * Checks if the browser is a mobile platform. Currently only checks for iphone.
     *
     * @return boolean
     */
    private function isMobile()
    {
        if (strstr($_SERVER['HTTP_USER_AGENT'], " AppleWebKit/") && strstr($_SERVER['HTTP_USER_AGENT'], " Mobile/")) {
            return true;
        }
        return false;
    }

}
