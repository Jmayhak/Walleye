<?php
/*
 * walleye.console.php
 *
 * Used for general logging and logging errors. All errors that are marked
 * for storage will be saved to /logs/access.log
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.8
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
     * @static
     * @param string $message the message to be logged
     * @param string $file the file this log occurred in
     * @param string $line the line this log occurred on
     * @param boolean $store whether or not to store this log in /logs/app.log
     * @param string $type the type of log
     * @return void
     */
    public static function log($message, $file = 'unknown file', $line = 'unknown line', $store = true, $type = 'log') {
        $logItem = array(
            'type' => $type,
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'date' => date('F j, Y, g:i a')
        );
        if ($store) {
            self::storeLog($logItem);
        }
        array_push(self::$logs, $logItem);
    }
    
    /**
     * Use this function to add an error log to the app.log file
     * @static
     * @param string $message
     * @param exception $ex
     * @param boolean $store
     * @return void
     */
    public static function logError($message, $ex, $store = true) {
        Console::log($message, $ex->getFile(), $ex->getLine(), $store, 'error');
    }
    
    /**
     * Returns all logs
     * @static
     * @return array
     */
    public static function getLogs() {
        return self::$logs;
    }
    
    /**
     * Takes an array and adds it to the logs/app.log file
     * @static
     * @param array $log
     * @return void
     */
    private static function storeLog($logItem) {
        $stream = fopen(Walleye::getServerBaseDir() . 'logs/app.log', 'a');
        fwrite($stream, print_array($logItem) . "\n");
        fclose($stream);
    }
}

?>