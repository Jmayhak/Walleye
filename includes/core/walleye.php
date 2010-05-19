<?php

if($wConfigOptions['PRODUCTION']) ini_set('zend.ze1_compatibility_mode', 0);
if($wConfigOptions['PRODUCTION']) ini_set('display_errors','0');

require($wConfigOptions['BASE'] . 'includes/config/config.php');
require($wConfigOptions['BASE'] . 'includes/config/routes.php');
require($wConfigOptions['BASE'] . 'includes/core/libraries/db/wdb.php');
require($wConfigOptions['BASE'] . 'includes/core/wmodel.class.php');
require($wConfigOptions['BASE'] . 'includes/core/wcontroller.class.php');

/**
 *
 */
class Walleye {

    const USER_SESSION = 'user';

    private static $instance;
    private $pqp;
    private $url;
	private $options = array();
    private $data = array();

    /**
     * Constructor for the events application
     */
    private function Walleye() {
        session_start();
        $this->pqp = new PhpQuickProfiler(PhpQuickProfiler::staticGetMicroTime());
        $this->data = array();
        $this->options = array();
    }

    /**
     * The options, handler, action, and data are set
     *
	* @param $options array
	* @param $url string
     * $return Walleye
     */
    public static function withOptions($options) {
	    $instance = new Walleye();
	    $this->url = $_SERVER["REQUEST_URI"];
        $instance->options = $options;
        $instance->data = $instance->getDataFromURL($this->url);
        return $instance;
    }

    /**
     * Start the application
     */
    public function run() {
        $this->route();
        if(!$wConfigOptions['PRODUCTION']) {
            $this->pqp->display(wDb::getInstance());
        }
    }

    /**
     * The specific controller is selected based on the controller given in the URL.
     *
     * @see includes/config/routes.php
     */
    public function route() {
	    //TODO write a preg match for the wRoutes array that creates a new controller that matches
    }

    /**
     * Checks if the browser is a mobile platform
     *
     * @return boolean
     */
    public function isMobile() {
        if (strstr($_SERVER['HTTP_USER_AGENT'], " AppleWebKit/") && strstr($_SERVER['HTTP_USER_AGENT'], " Mobile/")) {
            return true;
        }
        return false;
    }

	/**
	 * Returns the currently logged in user via sessions. If a user is not
	 * set then it sets the user and returns
	 *
	 * @see User::withSession()
	 * @return User
	 */
	public function getLoggedUser() {
	    if(!self::$user) {
	        self::$user = User::withSession();
	    }
	    return self::$user;
	}

	/**
	 * Sets the logged in user via Sessions. Use this after changing the session
	 * of a user after reauthentication to make sure on the next view render the
	 * user will be allowed access.
	 *
	 * @see User::withSession()
	 */
	public function setLoggedUser() {
	    self::$user = User::withSession();
	}
	
    /**
     *
     * @return Array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Takes a URL and returns the handler ex. /admin/test/adf?hello=yes would return admin
     *
     * @deprecated
     * @property $URL string
     * @return string
     */
    private function getHandlerFromURL($URL) {
        $withoutData = explode("?", $URL);
        $pathArray = explode("/", $withoutData[0]);
        return $pathArray[1];
    }

    /**
     * Takes a URL and returns the action ex. /admin/test/adf?hello=yes would return test/adf
     *
     * @deprecated
     * @property $URL string
     * @return array
     */
    private function getActionFromURL($URL) {
        $withoutData = explode("?", $URL);
        $pathArray = explode("/", $withoutData[0]);
        $action = array_slice($pathArray, 2);
        return $action;
    }

    /**
     * Returns either the GET or POST data depending on the request type
     *
     * @return array
     */
    private function getDataFromURL() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            return $_POST;
        }
        return $_GET;
    }

    /**
     * Returns Controller object in the form: Handler: '' Action: '' Data: '' View: '' Values: ''
     *
     * @return string
     */
    public function toString() {
        return '';
    }

}

?>
