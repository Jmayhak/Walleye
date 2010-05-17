<?php

/**
 * 
 */
class User_controller {

    /**
     * A new instance is created of the parent object.
     */
    function User_controller() {

    }

    /**
     * Perform the action in the URL
     */
    public function doAction() {
        $controller = Controller::getInstance();
        $action = $controller->getAction();
        $data = $controller->getData();
        switch($action[0]) {
            case "login":
                $this->login();
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
        $controller = Controller::getInstance();
        if($controller->getLoggedUser()->getUid() != ID_OF_UNKNOWN_USER) {
            unset($_SESSION[USER_SESSION]);
            $controller->setLoggedUser();
        }
        $controller->redirect(HOME_URL_PATH);
    }

    /**
     * Logs in the user
     * Sets the view to the the return url if given, the homepage, or the login
     * view if authentication fails
     */
    public function login() {
        $controller->view = LOGIN_VIEW;
        $controller = Controller::getInstance();
        $data = $controller->getData();
        if(isset($data["userName"]) && isset($data["password"])) {
            $user = User::withUserNameAndPassword($data["userName"], md5($data["password"]));
            if($user->getUid() != ID_OF_UNKNOWN_USER) {
                if(isset($data["return_url"]) && $data["return_url"] != "") {
                    $controller->redirect($data["return_url"]);
                }
                else {
                    $controller->redirect(HOME_URL_PATH);
                }
                $controller->setLoggedUser();
            }
            else {
                $controller->values = Array("login_message"=>"Wrong user name and password combination");
            }
        }
        else {
            $controller->values = Array("login_message"=>"Wrong user name or password combination");
        }
    }

    /**
     * Returns a string formatted as follows: view: 'view', values: 'values'
     *
     * @return string
     */
    public function toString() {
        $returnValue = "";
        foreach(Controller::getInstance()->values as $value) {
            $returnValue .= $value . " ";
        }
        return "view: '$this->view' " .
                "values: '$returnValue'";
    }
}
?>