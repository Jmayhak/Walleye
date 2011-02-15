<?php

namespace Walleye;

/**
 * walleye.database.php
 *
 * This is the class that handles all connections to the database.
 *
 * Uses MySQLi
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 */
class Database extends \mysqli
{

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
    public function __construct($db = null)
    {
        $dbOptions = Config::getDbOptions();
        $appOptions = Config::getAppOptions();

        $env = '';
        switch ($appOptions['ENVIRONMENT']) {
            case Config::DEVELOPMENT:
                $env = 'DEV';
                break;
            case Config::PRODUCTION:
                $env = 'PROD';
                break;
            default:
                $env = 'TEST';
                break;
        }
        $engine = strtolower($dbOptions[$env . '_ENGINE']);
        $server = $dbOptions[$env . '_SERVER'];
        $user = $dbOptions[$env . '_USER'];
        $password = $dbOptions[$env . '_PASS'];
        $port = $dbOptions[$env . '_PORT'];
        if (is_null($db)) {
            $database = $dbOptions[$env . '_DATABASE'];
        }
        else {
            $database = $db;
        }
        if($engine == 'mysql'){
            parent::mysqli($server, $user, $password, $database, $port);
        }
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

}