<?php

/**
 * walleye.database.php
 *
 * This is the class that handles all connections to the database. 
 *
 * Uses MySQLi
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @package Walleye
 */
class Walleye_database extends MySQLi {

    /**
     * Creates the Database object and sets the database connection info based on
     * the config file.
     *
     * You can connect to another database on the server listed in the config class by passing its
     * name in the constructor
     *
     * @access public
     * @see includes/walleye.config.php
     * @param string $db
     */
    public function __construct($db = null) {
        $dbOptions = Walleye_config::getDbOptions();
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
        parent::__construct($server, $user, $password, $database);
    }
    
    public function getResult($stmt) 
    { 
      $result = array(); 
      
      $metadata = $stmt->result_metadata(); 
      $fields = $metadata->fetch_fields(); 

      for (;;) 
      { 
        $pointers = array(); 
        $row = new stdClass(); 
        
        $pointers[] = $stmt; 
        foreach ($fields as $field) 
        { 
          $fieldname = $field->name; 
          $pointers[] = &$row->$fieldname; 
        } 
        
        call_user_func_array(mysqli_stmt_bind_result, $pointers); 
        
        if (!$stmt->fetch()) 
          break; 
        
        $result[] = $row; 
      } 
      
      $metadata->free(); 
      
      return $result; 
    }
    
}
