<?php

/**
 * The controller for a public or private API
 */
class Api extends \Walleye\Controller
{

    /*
     * @see Walleye_controller
     * @param string $url
     * @param array $data
     */
    public function __construct($url, $data)
    {
        $this->url = $url;
        $this->data = $data;
        $this->path = $this->getUrlPath($url);
        $this->handlers = array(
            'default' => 'bad_api_callHandler'
        );
    }

    /**
     * Returns xml for invalid api call
     */
    private function bad_api_callHandler()
    {
        $view = 'api/error.php';
        $this->useXmlHeader();
        $this->view($view);
    }
}

?>