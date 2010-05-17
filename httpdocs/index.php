<?php

define('PRODUCTION', true);
define('DEVELOPMENT', false);
define('TESTING', false);
define('LOCAL', false);

require("../config/file_locations.php");
require(CONFIG);
require(ROUTES);
require(CONSTANTS);
require(MODEL);
require(CONTROLLER);
require(PQP);

/**
 * The events application.
 */
class App {
    private $pqp;
    private $browser;

    /**
     * Constructor for the events application
     */
    public function App() {
        session_start();
        $this->pqp = new PhpQuickProfiler(PhpQuickProfiler::staticGetMicroTime());
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

$app = new App();
$app->start();

?>
