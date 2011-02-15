<?php

namespace Walleye;

/**
 * walleye.controller.php
 *
 * Every controller in your application should extend this class. This is because the routes() function
 * in Walleye tries to call the doHandler() function dynamically (it doesn't know what class it's calling
 * that function for).
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 */
abstract class Controller
{

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
     * The path of the URL. Use this property to get information from the URL.
     * ex. /user/view/1 will return array('user', 'view', '1')
     * @var array
     * @access protected
     */
    protected $path;

    /**
     * The handlers the controller that extends this abstract class should follow.
     * ex. array('/hello/' => 'helloHandler')
     * @var array
     * @access protected
     */
    protected $handlers = array();

    /**
     * The handler used
     * @var string
     */
    protected $handler;

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
     * @see Walleye_controller::$handlers
     * @return void
     */
    public function doHandler()
    {
        $this->handler = $handler = $this->getHandler();
        if (!is_null($handler) && method_exists($this, $handler)) {
            $this->$handler();
        }
    }

    /**
     * Call this function first in the doHandler() function to figure out which handler (function in the controller)
     * should be called.
     *
     * @final
     * @see Walleye_controller::$url
     * @see Walleye_controller::$handlers
     * @return string|null
     */
    final protected function getHandler()
    {
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
     * Takes the url and will spit back out an array separated by '/'
     * ex. $url = /user/1/edit will return array('user', '1', 'edit')
     *
     * @param string $url
     * @return array
     */
    final protected function getUrlPath($url)
    {
        $url_without_data_array = explode('?', $url);
        if (substr($url_without_data_array[0], 0, 1) == '/') {
            $url_without_data_array[0] = substr($url_without_data_array[0], 1);
        }
        if (substr($url_without_data_array[0], -1) == '/') {
            $url_without_data_array[0] = substr($url_without_data_array[0], 0, -1);
        }
        return explode('/', $url_without_data_array[0]);
    }

    /**
     * Checks if HTTP POST
     * @return boolean
     */
    protected function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') return true;
        return false;
    }

    /**
     * Checks if HTTP GET
     * @return boolean
     */
    protected function isGet()
    {
        if ($this->isPost()) return false;
        return true;
    }

    /**
     * This function will tell the view() function to display the 404 page which defaults to 404.php
     *
     * @see Walleye_controller::view()
     * @access protected
     * @return void
     */
    protected function error_404($view = '404.php', $values = array())
    {
        $this->view($view, $values);
    }

    /**
     * Redirects the browser to a specific URL. MUST be called before the <html>
     * is sent to the browser (before view())
     *
     * @final
     * @access protected
     * @see Walleye_controller::view()
     * @param string $URL the URL the browser should be redirected to. Send the url from domain.com/
     * @param array $data an optional key/value pair to be sent to the redirected page
     * @return void
     */
    final protected function redirect($URL = '', $data = array())
    {
        if (substr($URL, 0, 1) == '/') {
            $URL = substr($URL, 1);
        }
        $data_query = '';
        $alerts = Console::getAlerts();
        $logs = Console::getLogs();

        if (!\Walleye\Walleye::isProduction() && !empty($logs)) {
            // if the app is not in production, then get all logs including the alerts
            $data['logs'] = $logs;
        }
        else if (!empty($alerts)) {
            // else if there are alerts add them
            $data['logs'] = $alerts;
        }
        else {
            // else do nothing
        }

        if (!empty($data)) {
            $data_query = '?' . http_build_query($data);
        }
        header('Location: ' . Walleye::getDomain() . $URL . $data_query);
        exit();
    }

    /**
     * Changes the header to be text/xml. This is used for the api
     * @final
     * @access protected
     * @return void
     */
    final protected function useXmlHeader()
    {
        header("Content-Type: text/xml");
    }

    /**
     * Render a template through a basic php include
     *
     * ex. view('home/index.php', array('title'=>'Title'));
     *
     * @access protected
     * @param string $view the view to be rendered.
     * @param array $values the values to be shown on the view
     * @return void
     */
    protected function view($view, $values = array())
    {
        if (!Walleye::isProduction()) {
            $values['logs'] = Console::getLogs();
        }
        else {
            $values['logs'] = Console::getAlerts();
        }
        include(Walleye::getServerBaseDir() . 'includes/app/views/' . $view);
    }
}
