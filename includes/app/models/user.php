<?php

namespace App\Models;

/**
 * An example of extending the Walleye User Class.
 */
class User extends \Walleye\User
{

    /**
     * A property that only this class has access to
     * @var string
     */
    private $property;

    /**
     *
     */
    public function __construct($id)
    {
       $this->property = 'Jonathan Mayhak';
       parent::__construct($id);
    }

    /**
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    public static function whoAmI()
    {
        return __CLASS__;
    }
}
