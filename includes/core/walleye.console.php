<?php

namespace Walleye;

/*
 * walleye.console.php
 *
 * Used for general logging and logging errors. All errors that are marked
 * for storage will be saved to the Logs table in the db
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 */
class Console
{

    const ALERT = 'alert';

    const LOG = 'log';

    const ERROR = 'error';

    /**
     * Holds all logs that are going to be sent to the browser
     * @var array
     * @access protected
     */
    protected static $logs = array();

    /**
     * Use this function to add a general log to the Logs table
     *
     * @static
     * @param string $message
     * @param string $file
     * @param string $line
     * @param boolean $store
     */
    public static function log($message, $file = 'unknown file', $line = 'unknown line', $store = true, $type = 'log')
    {
        $logItem = array(
            'type' => $type,
            'message' => $message,
            'file' => $file,
            'line' => $line
        );
        $options = \Walleye\Config::getAppOptions();
        if ($store && $options['LOG_ERRORS']) {
            $log_id = self::storeLog($logItem);
            if ($log_id) {
                $logItem['id'] = $log_id;
            }
        }
        self::$logs[] = $logItem;
    }

    /**
     * Use this function to add an error log to the Logs table
     *
     * @static
     * @param string $message
     * @param string $file
     * @param string $line
     * @param boolean $store
     */
    public static function logError($message, $file = 'unknown file', $line = 'unknown line', $store = true)
    {
        Console::log($message, $file, $line, $store, 'error');
    }

    /**
     * Use this function to alert the currently logged in user of something via gritter
     *
     * @static
     * @param string $message
     * @param string $file
     * @param string $line
     * @param bool $store
     * @return void
     */
    public static function alert($message, $file = 'unknown file', $line = 'unknown line', $store = false)
    {
        Console::log($message, $file, $line, $store, 'alert');
    }

    /**
     * Returns all logs
     * @static
     * @return array
     */
    public static function getLogs()
    {
        return self::$logs;
    }

    /**
     * Returns only the alert logs
     * @static
     * @return array
     */
    public static function getAlerts()
    {
        $alerts = array();
        foreach (self::$logs as $log) {
            if ($log['type'] == Console::ALERT) {
                $alerts[] = $log;
            }
        }
        return $alerts;
    }

    /**
     * Removes all logs and leaves an empty array
     */
    public static function resetLogs()
    {
        self::$logs = array();
    }

    /**
     * Takes an array representing a log and inserts it into the Logs table in the db
     * @static
     * @param array $logItem
     * @return int
     */
    private static function storeLog($logItem)
    {
        $db = new \Walleye\Database();
        $insert_log_stmt = $db->prepare('INSERT INTO Logs (user_id, type, line, file, message) VALUES (?, ?, ?, ?, ?)');
        $user_id = (is_null(\Walleye\User::getLoggedUser())) ? 0 : \Walleye\User::getLoggedUser()->getId();
        $insert_log_stmt->bind_param('issss', $user_id, $logItem['type'], $logItem['line'], $logItem['file'], $logItem['message']);
        if ($insert_log_stmt->execute()) {
            return $db->insert_id;
        }
        return 0;
    }
}
