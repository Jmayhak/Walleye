<?php

/**
 * This is the controller that handles basic page requests like the about, contact, index, etc pages.
 *
 */
class cSite extends wController {

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
     * This function handles the action passed by Walleye
     */
    public function doAction() {
        switch ($this->action[0]) {
            case 'home':
                break;
            default:
                $this->view();
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