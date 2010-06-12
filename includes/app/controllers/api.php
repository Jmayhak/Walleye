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
        $this->handlers = array(
            '/^(\/api\/prayer\/insert)$/' => 'insertPrayerRequestHandler',
            '/^(\/api\/prayer\/getAll)$/' => 'getAllPrayerRequestsHandler',
            '/^(\/api\/prayer\/get)$/' => 'getPrayerRequestHandler',
            '/^(\/api\/prayer\/count)$/' => 'countHandler',
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

    /**
     * @return void
     */
    private function insertPrayerRequestHandler() {
        $this->useXmlHeader();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $message = (isset ($this->data['message'])) ? $this->data['message'] : '';
            $email = (isset ($this->data['email'])) ? $this->data['email'] :  '';
            $name = (isset ($this->data['name'])) ? $this->data['name'] :  'annoymous';
            $prayer_request = new PrayerRequest();
            $prayer_request->name = $name;
            $prayer_request->email = $email;
            $prayer_request->message = $message;
            $prayer_request->commit();
        }
        $values = array(
            'response' => 'success'
        );
        $this->view('api/insert.php', $values);
    }

    /**
     * @return void
     */
    private function getPrayerRequestHandler() {

    }

    /**
     * @return void
     */
    private function getAllPrayerRequestsHandler() {
        $this->useXmlHeader();
        $values = array();
        $values['prayer_requests'] = array();
        $db = new Walleye_database();
        foreach ($db->query('SELECT id FROM PrayerRequests') as $prayer_request) {
            array_push($values['prayer_requests'], PrayerRequest::withId($prayer_request['id']));
        }
        $this->view('api/get.php', $values);
    }

    /**
     * @return void
     */
    private function countHandler() {

    }

}

?>