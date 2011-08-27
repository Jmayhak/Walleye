<?php

namespace Walleye;

/**
 * walleye.controller.php
 *
 * Every controller in your application should extend this class. This is because the routes() function
 * in Walleye tries to call the doHandler() function dynamically (it doesn't know what class it's calling
 * that function for).
 *
 * @author    Jonathan Mayhak <Jmayhak@gmail.com>
 * @package    Walleye
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
     * The handler used by the controller
     * @var array
     */
    protected $handler = array();

    /**
     * Eventually this will hold all values but for now it only holds js
     *
     * @var array
     */
    private $values = array();

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
     * Will return an array(route, handler)
     * @see Walleye_controller::$handlers
     * @return array
     */
    public function doHandler()
    {
        $this->path = $this->getUrlPath($this->url);
        $this->handler = $handler = $this->getHandler();
        if (!is_null($handler[1]) && method_exists($this, $handler[1])) {
            $this->$handler[1]();
        }
        return $this->handler;
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
                return array($route, $handler);
            }
            else {
                if (preg_match($route, $this->url)) {
                    return array($route, $handler);
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
        if ($_SERVER['REQUEST_METHOD'] == 'GET') return true;
        return false;
    }

    /**
     * Checks if HTTP DELETE
     * @return bool
     */
    protected function isDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') return true;
        return false;
    }

    /**
     * Checks if HTTP PUT
     * @return bool
     */
    protected function isPut()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') return true;
        return false;
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
		header("HTTP/1.0 404 Not Found");
        $this->view($view, $values);
    }

    /**
     * Redirects the browser to a specific URL. MUST be called before the <html>
     * is sent to the browser (before view())
     *
     * @access protected
     * @param string $URL the URL the browser should be redirected to. Send the url from domain.com/
     * @param array $data an optional key/value pair to be sent to the redirected page
     * @return void
     */
    protected function redirect($URL = '', $data = array())
    {
        if (substr($URL, 0, 1) == '/') {
            $URL = substr($URL, 1);
        }
        $data_query = '';
        $alerts = Console::getAlerts();
        $logs = Console::getLogs();

        if (Walleye::getInstance()->isProduction() == false && empty($logs) == false) {
            // if the app is not in production, then get all logs including the alerts
            $data['logs'] = $logs;
        }
        else if (empty($alerts) == false) {
            // else if there are alerts add them
            $data['logs'] = $alerts;
        }
        else {
            // else do nothing
        }

        if (empty($data) == false) {
            $data_query = '?' . http_build_query($data);
        }

        // stop redirects so the app can be examined
        $appOptions = Walleye::getInstance()->getAppOptions();
        if ($appOptions['PRINT_APP_INFO_ON_LOAD'] == false) {
            header('Location: ' . Walleye::getInstance()->getDomain() . $URL . $data_query);
        }
        else {
            //Console::log('Redirect: ' . Walleye::getDomain() . $URL . $data_query);
        }
        exit();
    }

    /**
     * Changes the header to be text/xml
     * @access protected
     * @return void
     */
    final protected function useXmlHeader()
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-Type: text/xml');
    }

    /**
     * Changes the header to be application/json
     * @access protected
     * @return void
     */
    final protected function useJsonHeader()
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-Type: application/json');
    }

    /**
     * Use this function to add javascript to the views
     *
     * Just pass the location from httpdocs/js/
     *
     * @param string $loc
     */
    final protected function addJS($loc)
    {
        if (file_exists($script = Walleye::getInstance()->getServerBaseDir() . 'httpdocs/js/' . $loc)) {
            $scripts = $this->values['js'];
            $scripts[] = $script;
            $this->values['js'] = $scripts;
        }
        else {
            Console::logError('Passed a javascript that cannot be found: ' . $loc);
        }
    }

    /**
     * Render a view through a basic php include
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
        if (Walleye::getInstance()->isProduction() == false) {
            $values['logs'] = Console::getLogs();
        }
        else {
            $values['logs'] = Console::getAlerts();
        }
        include(Walleye::getInstance()->getServerBaseDir() . 'includes/app/views/' . $view);
    }
}

/* End of file */
