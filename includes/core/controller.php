<?php

/**
 *
 */
class Controller {

    private $handler;
    private $action = array();
    private $data = array();
    private static $user;
    private static $instance;
    public $view;
    public $values = array();

    /**
     * Creates the default Controller with view set to the BASE_INDEX
     */
    private function  __construct() {
        $this->handler = '';
        $this->action = array();
        $this->data = array();
        $this->view = BASE_INDEX;
        $this->values = array();
    }

    /**
     * Used to create a singleton instance of Controller.
     *
     * @return Controller
     */
    public static function getInstance() {
        if(!self::$instance) {
            self::$instance = new Controller();
        }
        return self::$instance;
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
     * Logs out the currently logged user
     */
    public function logout() {

    }

    /**
     * Logs in the currently logged user
     */
    public function login() {

    }

    /**
     * The handler, action, and data are set via the URL
     *
     * @property $URL string
     * @return Controller an instance of Controller with the handler, action,
     * and data set
     */
    public static function withURL($URL) {
        $instance = Controller::getInstance();
        $instance->handler = $instance->getHandlerFromURL($URL);
        if(!is_numeric($instance->handler)) {
            $instance->action = $instance->getActionFromURL($URL);
        }
        $instance->data = $instance->getDataFromURL();
        return $instance;
    }

    /**
     *
     * @return String
     */
    public function getHandler() {
        return $this->handler;
    }

    /**
     *
     * @return Array
     */
    public function getAction() {
        return $this->action;
    }

    /**
     *
     * @return Array
     */
    public function getData() {
        return $this->data;
    }

    /**
     *  Render a template and print the logged in user to PQP
     */
    private function doRender() {
        require($this->view);
    }

    /**
     * The specific controller is selected based on the controller given in the URL
     */
    public function doHandler() {
        switch($this->handler) {
            case "admin":
                break;
            case "api":
                break;
            case "user":
                $user = new User_controller();
                $user->doAction();
                break;
            case "login":
                $this->view = LOGIN_VIEW;
                if($this->getLoggedUser()->getUid() != ID_OF_UNKNOWN_USER) {
                    $this->view = BASE_INDEX;
                }
                break;
            case "logout":
                $user = new User_controller();
                $user->logout();
                break;
            default:
                break;
        }
        $this->doRender();
    }

    /**
     * Renders the mobile site instead of the full app.
     */
    public function doMobile() {
        $this->view = MOBILE_VIEW;
        $this->doRender();
    }

    /**
     * Takes a URL and returns the handler ex. /admin/test/adf?hello=yes would return admin
     *
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
     * Redirects the browser to a specific URL. MUST be called before the <html>
     * is sent to the browser (before rendering)
     *
     * @property $URL the URL the browser should be redirected to. Send the full URL
     * @see Controller::doRender()
     */
    public static function redirect($URL) {
        header("Location: $URL");
        exit();
    }

    /**
     * Returns Controller object in the form: Handler: '' Action: '' Data: '' View: '' Values: ''
     *
     * @return string
     */
    public function toString() {
        $actions = "";
        $view = "";
        $count = 0;
        foreach($this->getAction() as $action) {
            if($count++ + 1 == count($this->getAction())) {
                $actions .= $action;
            }
            else {
                $actions .= $action . " ";
            }
        }
        return "Handler: '$this->handler' " .
                "Action: '$actions' " .
                "Data: '$this->data' " .
                "View: '$view' " .
                "Values: '$this->values'";
    }
}

?>