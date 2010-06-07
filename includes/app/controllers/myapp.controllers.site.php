<?php

class MYAPP_controllers_site extends Walleye_controller {

    public function __construct($action, $data) {
        $this->action = $action;
        $this->data = $data;
    }

    public function doAction() {
         $this->view();
    }
}

?>