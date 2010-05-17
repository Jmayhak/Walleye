<?php

class Exception_model extends Exception
{

    public $file;
    public $line;

    public function Exception_model()
    {
        /**
         * Exception_controller constructor
         */
        parent::__Construct();
    }
}

class UnknownUserException extends Exception_model
{
    function UnknownUserException($file, $line, $message = "Unknown user")
    {
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
    }
}

class UnknownEventException extends Exception_model
{
    function UnknownEventException($file, $line, $message = "Unknown event")
    {
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
    }
}

class UnknownDependentException extends Exception_model
{
    function UnknownDependentException($file, $line, $message = "Unknown event")
    {
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
    }
}

class UpdateEventException extends Exception_model
{
    function UpdateEventException($file, $line, $message = "UpdateEventException: unable to update user")
    {
        /**
         * Creates new User Exception
         */
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
    }
}

class UpdateUserException extends Exception_model
{
    function UpdateUserException($file, $line, $message = "UpdateUserException: unable to update user")
    {
        /**
         * Creats new User Exception
         */
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
    }
}

class UpdateDependentException extends Exception_model
{
    function UpdateDependentException($file, $line, $message = "UpdateUserException: unable to update user")
    {
        /**
         * Creats new Dependent Exception
         */
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
    }
}

class UnknownUserSessionException extends Exception_model
{
    function UnknownUserSessionException($file, $line, $message = "Unknown User Session")
    {
        $this->file = $file;
        $this->line = $line;
        $this->message = $message;
    }
}

?>