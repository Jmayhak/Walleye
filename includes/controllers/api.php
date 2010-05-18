<?php

/**
 * 
 */
class Api_controller {

    /**
     * creates new instance of self and parent
     */
    function Api_controller() {

    }

    /**
     * Perform the action in the URI
     */
    public function doAction() {
        switch($this->getAction()) {
            default:
                break;
        }
    }
}

?>