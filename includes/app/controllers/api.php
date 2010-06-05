<?php

/**
 * This is where all requests to the internal api should be handled. 
 */
class cApi extends wController {

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
     * Perform the action passed by Walleye
     */
    public function doAction() {
        $walleye = Walleye::getInstance();
        $actions = $walleye->getActions();
        switch ($actions[0]) {
            default:
                break;
        }
    }

    /**
     * @return string
     */
    public function __toString() {
        return '';
    }

}

?>