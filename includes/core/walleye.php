<?php

if(!PRODUCTION) ini_set('zend.ze1_compatibility_mode', 0);
if(PRODUCTION) ini_set('display_errors','0');

require(DB_CONFIG);
require(DB_SQL_CORE);
require(DB_SQL_WRAPPER);
require(MODEL);
require(ROUTES);


//PQP
define('PQP', SERVER_PLUGINS_ROOT . 'pqp/pqp.php');
define('PQP_DISPLAY', 'display.php');
define('PQP_CONSOLE', 'console.php');

/**
 *
 */
class Walleye {

    const ID_OF_UNKNOWN_USER = 0;
    const USER_SESSION = 'user';

    private $pqp;
    private $browser;
    private $options;

    /**
     * Constructor for the events application
     */
    public function Walleye($options) {
        session_start();
        $this->pqp = new PhpQuickProfiler(PhpQuickProfiler::staticGetMicroTime());
    }

    /**
     *
     */
    public static function withOptions($options) {
        $this->options = $options;
    }

    /**
     * Start the application
     */
    public function start() {
        $controller = Controller::withURL($_SERVER["REQUEST_URI"]);
        if($this->isMobile()) {
            $controller->doMobile();
        }
        else {
            $controller->doHandler();
        }
        Console::log($controller->toString());
        if(!PRODUCTION) {
            $this->pqp->display(Model::getInstance());
        }
    }

    /**
     * Checks if the browser is a mobile platform
     *
     * @return boolean
     */
    public function isMobile() {
        if (strstr($_SERVER['HTTP_USER_AGENT'], " AppleWebKit/") && strstr($_SERVER['HTTP_USER_AGENT'], " Mobile/")) {
            $this->browser = IPHONE_BROWSER;
            return true;
        }
        return false;
    }

}

?>
