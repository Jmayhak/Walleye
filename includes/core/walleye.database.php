<?php

namespace Walleye;

/**
 * walleye.database.php
 *
 * This is the class that handles all connections to the database.
 *
 * Uses MySQLi
 *
 * @author    Jonathan Mayhak <Jmayhak@gmail.com>
 * @package    Walleye
 */
class Database extends \mysqli
{
    private static $me;

    /**
     * Creates the Database object and sets the database connection info based on
     * the config file.
     *
     * You can connect to another database on the server listed in the config class by passing its
     * name in the constructor
     *
     * Only supports Mysql databases.
     *
     * @see includes/walleye.config.php
     * @param string $db
     */
    private function __construct($db = null)
    {
        $dbOptions = Walleye::getInstance()->getDbOptions();

        $server = $dbOptions['SERVER'];
        $user = $dbOptions['USER'];
        $password = $dbOptions['PASS'];
        $port = $dbOptions['PORT'];
        if (is_null($db)) {
            $database = $dbOptions['DATABASE'];
        }
        else {
            $database = $db;
        }
        parent::mysqli($server, $user, $password, $database, $port);
    }

    /**
     * Makes sure the database is handled as a singleton. This function will give you
     * the instance of the database.
     *
     * @return Database
     */
    public static function getInstance($db = null)
    {
        if (!self::$me) {
            self::$me = new Database($db);
        }
        return self::$me;
    }

    /**
     * Returns all the selected rows as an array full of objects
     * @param MySQLi_STMT $stmt
     * @return array
     */
    public function getResult($stmt)
    {
        if (is_a($stmt, 'MySQLi_STMT')) {
            $result = array();

            $metadata = $stmt->result_metadata();
            $fields = $metadata->fetch_fields();

            for (; ;)
            {
                $pointers = array();
                $row = new \stdClass();

                $pointers[] = $stmt;
                foreach ($fields as $field)
                {
                    $fieldname = $field->name;
                    $pointers[] = &$row->$fieldname;
                }

                call_user_func_array('mysqli_stmt_bind_result', $pointers);

                if (!$stmt->fetch())
                    break;

                $result[] = $row;
            }

            $metadata->free();

            return $result;
        }
        return array();
    }

    /**
     * Gets the first row from a select statement
     * @param MySQLi_STMT $stmt
     * @return stdClass
     */
    public function getRow($stmt)
    {
        $result = $this->getResult($stmt);
        if ($result && !empty($result)) {
            return $result[0];
        }
        return $result;
    }

    /**
     * @return null|string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

}

/* End of file */
