<?php

/**
 * walleye.controller.php
 *
 * Every controller in your application should extend this class. This is because the routes() function
 * in Walleye tries to call the doAction() function dynamically (it doesn't know what class it's calling
 * that function for).
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @package Walleye
 */
abstract class Walleye_controller {

    /**
     * Either the GET or POST array
     * @var array
     * @access protected
     */
    protected $data = array();

    /**
     * the URL
     * @var string
     * @access protected
     */
    protected $url;
    
    /**
     * The actions in the URL. Use this property to get information from the URL.
     * ex. /user/view/1 will return array('user', 'view', '1')
     * @var array
     * @access protected
     */
    protected $actions;

    /**
     * The routes the controller that extends this abstract class should follow
     * @var array
     * @access protected
     */
    protected $handlers = array();

    /**
     * Note: when handlers array is created do not forget to define a default handler called 'default'
     *
     * @abstract
     * @access protected
     * @param array $data
     * @param string $url
     */
    abstract protected function __construct($url, $data);

    /**
     * Since Walleye is based on PHP 5.1.6 we cannot perform late static binding and stick doAction and
     * getHandler together. Look for an update in future versions of Walleye.
     *
     * @abstract
     * @access protected
     * @return void
     */
    abstract protected function doAction();

    /**
     * Call this function first in the doAction() function to figure out which handler (function in the controller)
     * should be called.
     *
     * @final
     * @see Walleye_controller::$url
     * @see Walleye_controller::$handlers
     * @return string|null
     */
    final protected function getHandler() {
        foreach ($this->handlers as $route => $handler) {
            if ($route == 'default') {
                return $handler;
            }
            else {
                if (preg_match($route, $this->url)) {
                    return $handler;
                }
            }
        }
        return null;
    }
    
    /**
     * Takes the url and will spit back out an array seperated by '/'
     *
     * @see Walleye_controller::$actions;
     * @param string $url
     * @return array
     */
    final protected function getActionsFromUrl($url) {
        $url_without_data_array = explode('?', $url);
        return explode('/', $url_without_data_array[0]);
    }

    /**
     * This function will tell the view() function to display the 404 page which defaults to 404.php
     *
     * @see Walleye_controller::view()
     * @access protected
     * @return void
     */
    protected function error_404($view = '404.php') {
        $this->view($view, array());
    }

    /**
     * Redirects the browser to a specific URL. MUST be called before the <html>
     * is sent to the browser (before view())
     *
     * @final
     * @access protected
     * @see Walleye_controller::view()
     * @param string $URL the URL the browser should be redirected to. Send the url from domain.com/
     * @return void
     */
    final protected function redirect($URL = '') {
        header('Location: ' . Walleye::getDomain() . $URL);
        exit();
    }

    /**
     * Changes the header to be text/xml. This is used for the api
     */
    final protected function useXmlHeader() {
        header("Content-Type: text/xml"); 
    }

    /**
     * Render a template through a basic php include
     *
     * ex. view('home/index.php', array('title'=>'Title'));
     *
     * @final
     * @access protected
     * @param string $view the view to be rendered.
     * @param array $values the values to be shown on the view
     * @param string $dir the location from includes/app/views/
     * @return void
     */
    final protected function view($view, $values = array()) {
        if (!Walleye::isProduction()) {
            $values['logs'] = Console::getLogs();
        }
        include(Walleye::getServerBaseDir() . 'includes/app/views/' . $view);
    }
}

?>