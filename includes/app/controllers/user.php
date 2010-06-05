<?php

/**
 *
 */
class cUser extends wController {

    /**
     * Creates a new cUser and sets the action and data in order to doAction() later.
     *
     * @param array $action
     * @param array $data
     */
    public function __construct($action, $data) {
        $this->action = $action;
        $this->data = $data;
    }

    /**
     * Perform the action in the URL
     *
     * @todo this needs extreme work
     */
    public function doAction() {
        switch ($this->action[0]) {
            case "login":
                $this->login();
                break;
            case "logout":
                $this->logout();
                break;
            default:
                break;
        }
    }

    /**
     * Logs out the currently logged user.
     * Sets the view to be the homepage
     */
    public function logout() {
        if (mUser::getLoggedUser()->getUid() != mUser::ID_OF_UNKNOWN_USER) {
            unset($_SESSION[mUser::USER_SESSION]);
            mUser::setLoggedUserWithSession();
        }
        $this->view('index.php');
    }

    /**
     * Logs in the user
     * Sets the view to the the return url if given, the homepage, or the login
     * view if authentication fails
     */
    public function login() {
        $view = 'user/login.php';
        $values = array();
        $data = $this->data;
        if (isset($data["userName"]) && isset($data["password"])) {
            //TODO send over password post encryption
            $user = mUser::withUserNameAndPassword($data["userName"], md5($data["password"]));
            if ($user->getUid() != mUser::ID_OF_UNKNOWN_USER) {
                mUser::setLoggedUserWithSession();
                if (isset($data["return_url"]) && $data["return_url"] != "") {
                    $this->redirect($data["return_url"]);
                }
                else {
                    $view = 'index.php';
                }
            }
            else {
                $values["login_message"] = "Wrong user name and password combination";
            }
        }
        else {
            $values["login_message"] = "Wrong user name or password combination";
        }
        $this->view($view, $values);
    }

    /**
     * Returns a string formatted as follows: view: 'view', values: 'values'
     *
     * @return string
     */
    public function __toString() {
        return '';
    }
}

?>