<?php

namespace controllers;

/**
 * 
 */
class User extends wController implements iControler {

    /**
     * A new instance
     */
    function User() {
	    super::__contruct();
    }

    /**
     * Perform the action in the URL
     */
    public function doAction() {
        $walleye = Walleye::getInstance();
		        $actions = $walleye->actions;
		        $data = $walleye->data;
		        switch($actions[0]) {
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
        $walleye = Walleye::getInstance();
		        if($walleye->getLoggedUser()->getUid() != constant('\models\User::' . ID_OF_UNKNOWN_USER)) {
		            unset($_SESSION[constant('\models\User::' . USER_SESSION)]);
		            $walleye->setLoggedUser();
		        }
		        $this->view('index.php', array());
    }

    /**
     * Logs in the user
     * Sets the view to the the return url if given, the homepage, or the login
     * view if authentication fails
     */
    public function login() {
        $view = 'user/login.php';
		        $values = array();
		        $walleye = Walleye::getInstance();
		        $data = $walleye->getData();
		        if(isset($data["userName"]) && isset($data["password"])) {
		            //TODO send over password post encryption
		            $user = \models\User::withUserNameAndPassword($data["userName"], md5($data["password"]));
		            if($user->getUid() != constant('\models\User::' . ID_OF_UNKNOWN_USER)) {
		                $walleye->setLoggedUser();
		                if(isset($data["return_url"]) && $data["return_url"] != "") {
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