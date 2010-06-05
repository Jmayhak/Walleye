<?php

abstract class wController {

    protected $data;
    protected $action;

    abstract public function __construct($data, $action);

    abstract public function doAction();

    /**
     * Redirects the browser to a specific URL. MUST be called before the <html>
     * is sent to the browser (before view())
     *
     * @param string $URL the URL the browser should be redirected to. Send the full URL
     * @see Controller::doRender()
     */
    final public function redirect($URL) {
        header("Location: $URL");
        exit();
    }

    /**
     *  Render a template
     *
     * @todo make the default view shown a 404 page instead of the index
     * @param string $view the view to be rendered.
     * @param array $values the values to be shown on the view
     */
    final public function view($view = 'index.php', $values = array()) {
        include(Walleye::getServerBaseDir() . "includes/app/views/$view");
        exit();
    }
}

?>