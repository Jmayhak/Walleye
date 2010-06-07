<?php

/**
 * walleye.model.php
 *
 * Each model you create in your application should extend this class.
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
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
     * Every model in your application should have a toString function
     *
     * @abstract
     * @return string
     */
    abstract public function __toString();

}

?>