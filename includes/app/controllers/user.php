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
        $this->action = $url;
        $this->data = $data;
        $this->handlers = array(
            '/user\/login/' => 'loginHandler',
            '/user\/logout/' => 'logoutHandler',
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

}

?>