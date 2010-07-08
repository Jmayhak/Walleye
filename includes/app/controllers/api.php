<?php

class Api extends Walleye_controller {

    /**
     * Creates a new EFNEP_controllers_site and sets the url, data, and handlers in Walleye_controller
     * in order to perform doAction() later.
     *
     * Note: when handlers array is created do not forget to define a default handler called 'default'
     *
     * @see Walleye_controller
     * @param array $url
     * @param array $data
     */
    public function __construct($url, $data) {
        $this->url = $url;
        $this->data = $data;
        // !todo create a handler for bad api requests
        $this->handlers = array(
            'default' => 'indexHandler'
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