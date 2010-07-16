<?php
/*
 * walleye.console.php
 *
 * Used for general logging and logging errors. All errors that are marked
 * for storage will be saved to /logs/access.log
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
 * @package Walleye
 */
class Console {

    /**
     * Holds all logs that are going to be sent to the browser
     * @var array
     * @access protected
     */
    protected static $logs = array();

    /**
     * Use this function to add a general log
     * @param string $message
     * @param Exception $ex
     */
    public static function log($message, $file = 'unknown file', $line = 'unknown line', $store = true, $type = 'log') {
        $logItem = array(
            'type' => $type,
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'date' => date('F j, Y, g:i a')
        );
        $options = Walleye_config::getAppOptions();
        if ($store && $options['LOG_ERRORS']) {
            self::storeLog($logItem);
        }
        array_push(self::$logs, $logItem);
    }

    /**
     * Use this function to add an error log to the app.log file
     * @param string $message
     * @param exception $ex
     * @param boolean $store
     */
    public static function logError($message, $ex = null, $store = true) {
        if (is_null($ex)) {
            Console::log($message, 'unknown file', 'unknown line', $store, 'error');
        }
        else if (class_exists($ex)) {
            if (in_array('getFile', get_class_methods($ex)) && in_array('getLine', get_class_methods($ex))) {
                Console::log($message, $ex->getFile(), $ex->getLine(), $store, 'error');
            }
        }
        else {
            Console::log($message, 'unknown file', 'unknown line', $store, 'error');
        }
    }

    /**
     * Use this function to alert the currently logged in user of something
     * @static
     * @param string $message
     * @param string $file
     * @param string $line
     * @param bool $store
     * @return void
     */
    public static function alert($message, $file = 'unknown file', $line = 'unknown line', $store = false) {
        Console::log($message, $file, $line, $store, 'alert');
    }

    /**
     * Returns all logs
     * @return array
     */
    public static function getLogs() {
        return self::$logs;
    }

    /**
     * Takes an array and adds it to the logs/app.log file
     * @param array $log
     * @param string $location
     * @return void
     */
    private static function storeLog($logItem, $location = null) {
        if (is_null($location)) {
            $options = Walleye_config::getAppOptions();
            $location = $options['LOG_FILE'];
        }
        $stream = fopen(Walleye::getServerBaseDir() . $location, 'a');
        fwrite($stream, print_array($logItem) . "\n");
        fclose($stream);
    }
}

?>