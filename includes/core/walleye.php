<?php

namespace Walleye;

require('walleye.config.php');
require('walleye.console.php');
require('walleye.functions.php');
require('walleye.database.php');
require('walleye.model.php');
require('walleye.controller.php');
require('walleye.user.php');
require('walleye.email.php');

/**
 * walleye.php
 *
 * Handles the basic routing of URLs to their proper controller.
 *
 * @final
 * @author	Jonathan Mayhak <Jmayhak@gmail.com>
 * @package	Walleye
 */
final class Walleye
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
     * The environment the application is running in
     * @var string
     * @access private
     */
    private static $environment;

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
     * The queries that the app has logged
     * 
     * @var array
     * @access private
     */
    private $queries = array();

    /**
     * Starts the session, stores the post or get data, and the path given in the url
     */
    private function __construct()
    {
        $this->startTime = EXECUTION_TIME_START;
        
        $appOptions = Config::getAppOptions();
        $dbOptions = Config::getDbOptions();

        if ($appOptions['ENVIRONMENT'] == Config::PRODUCTION) {
            self::$environment = Config::PRODUCTION;

            session_start();
            
            $this->dbOptions = array(
                'ENGINE'	=> $dbOptions['PROD_ENGINE'],
                'SERVER'	=> $dbOptions['PROD_SERVER'],
                'USER'		=> $dbOptions['PROD_USER'],
                'PASS'		=> $dbOptions['PROD_PASS'],
                'DATABASE'	=> $dbOptions['PROD_DATABASE'],
                'PORT'		=> $dbOptions['PROD_PORT']
            );
            
            $this->appOptions = array(
        	    'BASE'						=>	$appOptions['BASE'],
        	    'DOMAIN'					=>	$appOptions['PROD_DOMAIN'],
        	    'ENVIRONMENT'				=>	$appOptions['ENVIRONMENT'],
        	    'LOG_ERRORS'				=>	$appOptions['LOG_ERRORS'],
        	    'REG_KEY_EXPIRE_TIME'		=>	$appOptions['REG_KEY_EXPIRE_TIME'],
                'SESSION_KEY_EXPIRE_TIME'	=>	$appOptions['SESSION_KEY_EXPIRE_TIME'],
                'EMAIL_FROM'				=>	$appOptions['EMAIL_FROM'],
                'PRINT_APP_INFO_ON_LOAD'	=>	$appOptions['PRINT_APP_INFO_ON_LOAD']
            );
            
            $this->data = $this->getDataFromUrl($_SERVER["REQUEST_URI"]);
            
            $url_array = explode('?', $_SERVER["REQUEST_URI"]); 
            $this->url = $url_array[0];
            
            $this->routes = \Walleye\Config::getRoutes();
            
            if (isset($this->appOptions['BASE'])) {
                self::$server_base_dir = $this->appOptions['BASE'];
            }
            
            if (isset($this->appOptions['DOMAIN'])) {
                self::$domain = $this->appOptions['DOMAIN'];
            }
        }
        else if ($appOptions['ENVIRONMENT'] == Config::DEVELOPMENT) {
            self::$environment = Config::DEVELOPMENT;

            session_start();
            
            $this->dbOptions = array(
                'ENGINE'	=> $dbOptions['DEV_ENGINE'],
                'SERVER'	=> $dbOptions['DEV_SERVER'],
                'USER'		=> $dbOptions['DEV_USER'],
                'PASS'		=> $dbOptions['DEV_PASS'],
                'DATABASE'	=> $dbOptions['DEV_DATABASE'],
                'PORT'		=> $dbOptions['DEV_PORT']
            );
            
            $this->appOptions = array(
        	    'BASE'						=>	$appOptions['BASE'],
        	    'DOMAIN'					=>	$appOptions['DEV_DOMAIN'],
        	    'ENVIRONMENT'				=>	$appOptions['ENVIRONMENT'],
        	    'LOG_ERRORS'				=>	$appOptions['LOG_ERRORS'],
        	    'REG_KEY_EXPIRE_TIME'		=>	$appOptions['REG_KEY_EXPIRE_TIME'],
                'SESSION_KEY_EXPIRE_TIME'	=>	$appOptions['SESSION_KEY_EXPIRE_TIME'],
                'EMAIL_FROM'				=>	$appOptions['EMAIL_FROM'],
                'PRINT_APP_INFO_ON_LOAD'	=>	$appOptions['PRINT_APP_INFO_ON_LOAD']
            );
            
            $this->data = $this->getDataFromUrl($_SERVER["REQUEST_URI"]);
            
            $url_array = explode('?', $_SERVER["REQUEST_URI"]);
            $this->url = $url_array[0];
            
            $this->routes = \Walleye\Config::getRoutes();
            
            if (isset($this->appOptions['BASE'])) {
                self::$server_base_dir = $this->appOptions['BASE'];
            }
            if (isset($this->appOptions['DOMAIN'])) {
                self::$domain = $this->appOptions['DOMAIN'];
            }
        }
        else {
            self::$environment = Config::TESTING;
            
            $this->appOptions = array(
        	    'BASE'						=>	$appOptions['BASE'],
        	    'ENVIRONMENT'				=>	$appOptions['ENVIRONMENT'],
        	    'LOG_ERRORS'				=>	$appOptions['LOG_ERRORS'],
        	    'REG_KEY_EXPIRE_TIME'		=>	$appOptions['REG_KEY_EXPIRE_TIME'],
                'SESSION_KEY_EXPIRE_TIME'	=>	$appOptions['SESSION_KEY_EXPIRE_TIME'],
                'EMAIL_FROM'				=>	$appOptions['EMAIL_FROM'],
                'PRINT_APP_INFO_ON_LOAD'	=>	$appOptions['PRINT_APP_INFO_ON_LOAD']
            );
            
            $this->dbOptions = array(
                'ENGINE'	=> $dbOptions['TEST_ENGINE'],
                'SERVER'	=> $dbOptions['TEST_SERVER'],
                'USER'		=> $dbOptions['TEST_USER'],
                'PASS'		=> $dbOptions['TEST_PASS'],
                'DATABASE'	=> $dbOptions['TEST_DATABASE'],
                'PORT'		=> $dbOptions['TEST_PORT']
            );
            
            $this->routes = \Walleye\Config::getRoutes();
            
            if (isset($this->appOptions['BASE'])) {
                self::$server_base_dir = $this->appOptions['BASE'];
            }
        }
    }
    
    public function __destruct()
    {   
        $this->logs = Console::getLogs();
        
        $this->queries = Console::getQueries();
    
        // log the time the application took to execute
        $start_time = $this->startTime;
        $end_time = $this->endTime = microtime();
        
        $start_time_array = explode(' ', $start_time);
        $end_time_array = explode(' ', $end_time);
        
        $time = ( ( $end_time_array[1] + $end_time_array[0] ) - ( $start_time_array[1] + $start_time_array[0] ) ) * 1000;
        
        // make time readable
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
		$this->executionTime = $ret = number_format($ret,3,'.','') . ' ' . $formats[$formatter];
		
		if ($this->appOptions['PRINT_APP_INFO_ON_LOAD'] == true && ! $this->isProduction() && ! $this->isTesting()) {
		    echo '<pre>';
		    print_r($this);
		    echo '</pre>';
		}
    }

    /**
     * Makes sure Walleye is handled as a singleton. This function will give you
     * the instance of Walleye.
     *
     * @return Walleye
     */
    public static function getInstance()
    {
        if (!self::$me) {
            self::$me = new Walleye();
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
    public static function getServerBaseDir()
    {
        return self::$server_base_dir;
    }

    /**
     * Gives the domain of this app
     * @return string
     */
    public static function getDomain()
    {
        return self::$domain;
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
    public static function isProduction()
    {
        if (self::$environment == Config::PRODUCTION) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public static function isTesting()
    {
        if (self::$environment == Config::TESTING) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean
     */
    public static function isDevelopment()
    {
        if (self::$environment == Config::DEVELOPMENT) {
            return true;
        }
        return false;
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
