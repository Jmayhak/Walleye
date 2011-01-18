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
class Database extends \mysqli {

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
    public function __construct($db = null) {
        $dbOptions = \Walleye\Config::getDbOptions();
        $engine = $dbOptions['ENGINE'];
        $server = $dbOptions['SERVER'];
        $user = $dbOptions['USER'];
        $password = $dbOptions['PASS'];
        if (is_null($db)) {
            $database = $dbOptions['DATABASE'];
        }
        else {
            $database = $db;
        }
        parent::mysqli($server, $user, $password, $database);
    }

    /**
     * Returns all the selected rows as an array full of objects
     * @param MySQLi_STMT $stmt
     * @return array
     */
    public function getResult($stmt) 
    { 
      $result = array(); 
      
      $metadata = $stmt->result_metadata(); 
      $fields = $metadata->fetch_fields(); 

      for (;;) 
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

    /**
     * Gets the first row from a select statement
     * @param MySQLi_STMT $stmt
     * @return stdClass|null
     */
    public function getRow($stmt)
    {
        $result = $this->getResult($stmt);
        if (!empty($result)) {
            return $result[0];
        }
        return null;
    }
    
}
