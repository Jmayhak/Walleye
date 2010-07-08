<?php

/**
 * walleye.model.php
 *
 * The general exception class that all exceptions created through this framework
 * should extend. This is because this exception will deal with logging for you.
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.8
 * @package Walleye
 */
abstract class Walleye_model_exception extends Exception {

}

/**
 * walleye.model.php
 *
 * Each model you create in your application should extend this class.
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.8
 * @package Walleye
 */
abstract class Walleye_model {

    /**
     * Every model in your application should have a constructor that takes an id to initialize. All
     * the properties that can be set should be set in the constructor
     *
     * @static
     * @abstract
     * @param int|string $id
     * @return self
     */
    abstract public static function withId($id);

    /**
     * After you are done setting properties in your model call this function. Implement this function
     * by updating the object in the database.
     *
     * @abstract
     * @return void
     */
    abstract public function commit();

    /**
     * Use this function to create a new row in the database for this instanct of this model.
     *
     * @abstract
     * @return self
     */
    abstract public static function create();

    /**
     * Every model in your application should have a toString function
     *
     * @abstract
     * @return string
     */
    abstract public function __toString();

}

?>