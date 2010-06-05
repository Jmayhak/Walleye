<?php

require($wConfigOptions['BASE'] . 'includes/core/libraries/db/wdb.php');
require($wConfigOptions['BASE'] . 'includes/core/libraries/pqp/pqp.php');

//TODO implement __autoload() for models and controllers instead of a strict require

//controllers
require($wConfigOptions['BASE'] . 'includes/app/controllers/api.php');
require($wConfigOptions['BASE'] . 'includes/app/controllers/site.php');
require($wConfigOptions['BASE'] . 'includes/app/controllers/user.php');

//models
require($wConfigOptions['BASE'] . 'includes/app/models/adult.php');
require($wConfigOptions['BASE'] . 'includes/app/models/task.php');
require($wConfigOptions['BASE'] . 'includes/app/models/user.php');
require($wConfigOptions['BASE'] . 'includes/app/models/youthgroup.php');

final class Walleye {

    private static $walleye_instance;

    private $action = array();
    private $data = array();
    private $url;

    private $pqp;
    private static $server_base_dir;

    private $appOptions = array();
    private $dbOptions = array();
    private $routes = array();

    //views
    const BASE_INDEX_VIEW = 'index.php';
    const BASE_HEADER_VIEW = '_header.php';
    const BASE_FOOTER_VIEW = '_footer.php';
    const BASE_SIDEBAR_VIEW = 'sidebar.php';

    //static
    const DEFAULT_STYLESHEET = '/style.css';
    const PQP_OVERLAY = '/plugins/pqp/overlay.gif';
    const PQP_CSS = '/plugins/pqp/pqp.css';
    const PQP_SIDE = '/plugins/pqp/side.png';

    /**
     * Starts the session, instantiates the pqp object, the post or get data, and the action given in the url
     */
    private function Walleye() {
        session_start();
        $this->pqp = new PhpQuickProfiler(PhpQuickProfiler::staticGetMicroTime());
        $this->data = $this->getDataFromUrl($_SERVER["REQUEST_URI"]);
        $this->action = $this->getActionFromUrl($_SERVER["REQUEST_URI"]);
        $this->url = $_SERVER["REQUEST_URI"];
    }

    /**
     * Makes sure Walleye is handled as a singleton. This function will give you
     * the instance of Walleye.
     *
     * @return Walleye
     */
    public static function getInstance() {
        if (!self::$walleye_instance) {
            self::$walleye_instance = new Walleye();
        }
        return self::$walleye_instance;
    }

    /**
     * Should be called directly after retrieving the Walleye object. This function performs the action
     * given in the url and shows pqp if not in production mode.
     */
    public function run() {
        $this->route();
        if (!$this->appOptions['PRODUCTION']) {
            $this->pqp->display(wDb::getInstance());
        }
    }

    /**
     * The specific controller is selected based on the controller given in the URL.
     *
     * @see includes/config/routes.php
     */
    private function route() {
        foreach ($this->routes as $route => $controller) {
            if ($route == 'default') {
                if (class_exists($controller) && in_array(('doAction'), get_class_methods($controller))) {
                    $instance = new $controller($this->action, $this->data);
                    $instance->doAction();
                }
                break;
            }
            else {
                if (preg_match($route, $this->url) && in_array(('doAction'), get_class_methods($controller))) {
                    if (class_exists($controller)) {
                        $instance = new $controller($this->action, $this->data);
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
     * Sets the application options (production mode? base dir? local?)
     *
     * @param array $appOptions
     */
    public function setAppOptions($appOptions) {
        $this->appOptions = $appOptions;
        if (isset($appOptions['BASE'])) {
            self::$server_base_dir = $appOptions['BASE'];
        }
    }

    /**
     * Sets the db options. The array must contain server, username, password, and database information.
     * After Walleye is updated the wDb instance is created and the db options are sent.
     *
     * @param array $dbOptions
     */
    public function setDbOptions($dbOptions) {
        $this->dbOptions = $dbOptions;
        wDb::getInstance($dbOptions);
    }

    /**
     * Use this to set the routes for this application. The routes must be in the form regexp => controller
     *
     * @param array $routes
     */
    public function setRoutes($routes) {
        $this->routes = $routes;
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
