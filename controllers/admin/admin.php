<?php

/**
 *
 */
class Admin_controller {

    /**
     * Creates a new instance of self and parent
     */
    function Admin_controller() {

    }

    /**
     * Performs the action in the URI. Also check if user role
     */
    public function doAction() {
        switch($this->getAction()) {
            default:
                break;
        }
    }
}

?>