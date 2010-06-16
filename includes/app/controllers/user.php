<?php

/**
 * user.php
 *
 * This class is used to handle routes related to user management.
 *
 * @package Prayer
 * @subpackage controllers
 */
class User extends Walleye_controller {

    /**
     * Creates a new cUser and sets the action and data in order to doAction() later.
     *
     * @param array $url
     * @param array $data
     */
    public function __construct($url, $data) {
        $this->url = $url;
        $this->data = $data;
        $this->handlers = array(
            '/^(\/user\/login)$/' => 'loginHandler',
            '/^(\/user\/logout)$/' => 'logoutHandler',
            'default' => 'error_404'
        );
    }

    /**
     * Performs a preg_match() on the handlers given in the constructor to find a match and then
     * dynamically calls the function given in the handlers array.
     *
     * @see Walleye_controller::$handlers
     * @return void
     */
    public function doAction() {
        $handler = $this->getHandler();
        if (!is_null($handler) && method_exists($this, $handler)) {
            $this->$handler();
        }
    }
    
    /**
     * Logs out the currently logged user.
     * Sets the view to be the homepage
     */
    public function logoutHandler() {
        if (Walleye_user::getLoggedUser()) {
            unset($_SESSION[Walleye_user::USER_SESSION]);
        }
        $this->redirect();
    }

    /**
     * Logs in the user
     * Sets the view to the the return url if given, the homepage, or the login
     * view if authentication fails
     */
    public function loginHandler() {
        $values = array();
        $data = $this->data;
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($data['username']) && isset($data['password'])) {
            // !TODO send over password post encryption
            $user = Walleye_user::withUsernameAndPassword($data['username'], md5($data['password']));
            if (!is_null($user)) {
                Walleye_user::setLoggedUserWithSession();
                if (isset($data['return_url']) && $data['return_url'] != '') {
                    $this->redirect($data['return_url']);
                }
                else {
                    $this->redirect();
                }
            }
            else {
                $values['login_message'] = 'Wrong user name and password combination';
                $this->view('user/login.php', $values);
            }
        }
        else {
            $this->view('user/login.php');
        }
    }
}

?>