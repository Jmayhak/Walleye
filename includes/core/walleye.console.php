<?php

/*
 * walleye.console.php
 *
 * Used for general logging and logging errors. All errors that are marked
 * for storage will be saved to the Logs table in the db
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
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
     * @param string $file
     * @param string $line
     * @param boolean $store
     */
    public static function log($message, $file = 'unknown file', $line = 'unknown line', $store = true, $type = 'log') {
        $logItem = array(
            'type' => $type,
            'message' => $message,
            'file' => $file,
            'line' => $line
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
     * @param string $file
     * @param string $line
     * @param boolean $store
     */
    public static function logError($message, $file = 'unknown file', $line = 'unknown line', $store = true) {
        Console::log($message, $file, $line, $store, 'error');
    }

    /**
     * Use this function to alert the currently logged in user of something via gritter
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
     * Takes an array representing a log and inserts it into the Logs table in the db
     * @param array $logItem
     * @return boolean
     */
    private static function storeLog($logItem) {
        $db = new Walleye_database();
        $insert_log_stmt = $db->prepare('INSERT INTO Logs (user_id, type, line, file, message) VALUES (?, ?, ?, ?, ?)');
        $user_id = (is_null(Walleye_user::getLoggedUser())) ? 0 : Walleye_user::getLoggedUser()->getId();
        $insert_log_stmt->bind_param('issss', $user_id, $logItem['type'], $logItem['line'], $logItem['file'], $logItem['message']);
        return $insert_log_stmt->execute();
    }
}
