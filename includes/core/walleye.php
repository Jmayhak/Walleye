<?php

require($wConfigOptions['BASE'] . 'includes/core/libraries/ez_sql/ez_sql_core.php');
require($wConfigOptions['BASE'] . 'includes/core/libraries/ez_sql/ez_sql_mysql.php');
require($wConfigOptions['BASE'] . 'includes/core/libraries/db/wdb.class.php');
require($wConfigOptions['BASE'] . 'includes/core/wmodel.class.php');
require($wConfigOptions['BASE'] . 'includes/core/wcontroller.class.php');
require($wConfigOptions['BASE'] . 'includes/core/libraries/pqp/pqp.php');

/**
 *
 */
class Walleye {

    private static $instance;
    private static $user;
    private $pqp;
    private $url;
    private $action = array();
    private $handler;
    public $options = array();
    public $dbOptions = array();
    public $routes = array();
    private $data = array();

    /**
     * Constructor for the events application
     */
    private function Walleye() {
        session_start();
        $this->pqp = new PhpQuickProfiler(PhpQuickProfiler::staticGetMicroTime());
        $this->url = $_SERVER["REQUEST_URI"];
        $this->data = $this->getDataFromUrl($this->url);
        $this->action = $this->getActionFromUrl($this->url);
    }

    /**
     * Makes sure Walleye is handled as a singleton. This function will give you
     * an instance (the only instance) of Walleye.
     *
     * @return Walleye
     */
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Walleye();
        }
        return self::$instance;
    }

    /**
     * Start the application
     */
    public function run() {
        $this->route();
        if (!$this->options['PRODUCTION']) {
            $this->pqp->display(wDb::getInstance());
        }
    }

    /**
     * The specific controller is selected based on the controller given in the URL.
     *
     * @see includes/config/routes.php
     */
    public function route() {
        foreach ($this->routes as $route => $handler) {
            if ($route == 'default') {
                $controller = new wController();
                $controller->view('index.php', array());
            }
            if (preg_match($route, $this->url)) {
                $this->handler = $handler;
                $instance = new $handler;
                $instance->doAction();
            }
        }
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
     * @see \models\User::withSession()
     * @return User
     */
    public function getLoggedUser() {
        if (!self::$user) {
            self::$user = \models\User::withSession();
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
        self::$user = \models\User::withSession();
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
     * Takes a URL and returns the action ex. /admin/test/adf?hello=yes would return test/adf
     *
     * @param $url string
     * @return array
     */
    private function getActionFromUrl($url) {
        $withoutData = explode("?", $url);
        $pathArray = explode("/", $withoutData[0]);
        $action = array_slice($pathArray, 2);
        return $action;
    }

    /**
     * Returns Controller object in the form: Handler: '' Action: '' Data: ''
     *
     * @return string
     */
    public function __toString() {
        return "Handler: '" . $this->handler .
                "Action: '" . $this->action .
                "Data: '" . $this->data;
    }

}

?>
