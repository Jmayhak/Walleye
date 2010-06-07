<?php

/**
 * walleye.controller.php
 *
 * Every controller in your application should extend this class. This is because the routes() function
 * in Walleye tries to call the doAction() function dynamically (it doesn't know what class it's calling
 * that function for). 
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
 * @package Walleye
 */
abstract class Walleye_controller {

    /**
     * Either the GET or POST array
     * @var array
     */
    protected $data;

    /**
     * The action given in the URL
     * @var array
     */
    protected $action;

    /**
     * @abstract
     * @param array $data
     * @param array $action
     * @return self
     */
    abstract public function __construct($data, $action);

    /**
     * @abstract
     * @return void
     */
    abstract public function doAction();

    /**
     * Redirects the browser to a specific URL. MUST be called before the <html>
     * is sent to the browser (before view())
     *
     * @param string $URL the URL the browser should be redirected to. Send the full URL
     * @see Walleye_controller::view()
     * @return void
     */
    final public function redirect($URL) {
        header("Location: $URL");
        exit();
    }

    /**
     *  Render a template
     *
     * @param string $view the view to be rendered.
     * @param array $values the values to be shown on the view
     * @return void
     */
    final public function view($view = 'index.php', $values = array()) {
        // TODO either track views in db here or as a trigger
        // TODO make the default view shown a 404 page
        include(Walleye::getServerBaseDir() . "includes/app/views/$view");
        exit();
    }
}

?>