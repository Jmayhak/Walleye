<?php

/**
 * site.php
 *
 * This controller handlers all basic/static requests like the contact page or the index page.
 *
 * @package Prayer
 * @subpackage controllers
 */
class Site extends Walleye_controller {

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
        $this->handlers = array(
            '/^(\/contact)$/' => 'contactHandler',
            '/^(\/)$/' => 'indexHandler',
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

    private function indexHandler() {
        $values = array();
        $values['prayer_requests'] = array();
        $db = Walleye::getInstance()->db();
        $get_id_stmt = $db->prepare('SELECT id FROM PrayerRequests');
        $get_id_stmt->execute();
        $get_id_stmt->bind_result($id);
        while ($get_id_stmt->fetch()) {
            array_push($values['prayer_requests'], PrayerRequest::withId($id));
        }
        $this->view('index.php', $values);
    }

    private function contactHandler() {
        $this->view('contact.php');
    }
}

?>