<?php

namespace models;

/**
 * User class. This class defines what a user looks like and interacts with the db to set/get data
 * @todo write a method to deal with creation of a new user
 */
class User extends wModel
{

const ID_OF_UNKNOWN_USER = 0;

    private $uid;
    private $userName;
    private $regDate;
    private $firstName;
    private $lastName;
private static $user;

    /************************
     * CONSTRUCTORS
     */
    
    /**
    * The base User constructor. Sets everything to nothing.
    */
    function User()
    {
        $this->uid = 0;
        $this->userName = "";
        $this->regDate = "";
        $this->firstName = "";
        $this->lastName = "";
    }
    
    /**
     * Creates the User model based on the session array. This IS the logged in user.
     * Use this constructor if the user is not doing something critical
     * 
     * @property $db Model
     * @property $session String
     * @return User
     * @todo check date_created on session
     */
    public static function withSession()
    {
        $session_id = 0;
        $user_session = 0;
        $db = Model::getInstance();
        if(isset($_SESSION[USER_SESSION]))
        {
            $session_id = $db->get_var("SELECT id FROM Sessions WHERE session_key = '" . $_SESSION[USER_SESSION] . "'");
            $user_session = $db->get_row("SELECT user_id, date_created FROM UserSessions WHERE session_id = '$session_id'");
        }
        if(isset($user_session["user_id"]))
        {
            try
            {
                $instance = User::withUid($user_session["user_id"]);
            }
            catch(UnknownUserException $ex)
            {
                Console::logError($ex, $ex->message);
            }
        }
        else
        {
            $instance = new User();
        }
        return $instance;
    }
    /**
     * Creates a new User based on the user cookie. This IS the logged in user
     * Use this constructor to maintain a connection if session is unavailable.
     *
     * @deprecated
     * @return User
     */
    public static function withCookie()
    {
        $user_id = 0;
        $db = Model::getInstance();
        if(isset($_COOKIE[USER_SESSION]))
        {
            $userSession = $db->get_row("SELECT user_id, date_created FROM UserSessions WHERE session_key = '$_COOKIE[USER_SESSION]'");
        }
        if($user_id)
        {
            try
            {
                $instance = User::withUid($userSession["user_id"]);
            }
            catch(UnknownUserException $ex)
            {
                Console::logError($ex, $ex->message);
            }
        }
        else
        {
            $instance = new User();
        }
        return $instance;
    }

    /**
     * Creates a new User based on the userName and password pair. This IS the logged in user
     * Use this constructor to log in the user and if the user is doing something critical.
     * A new session is started upon creation
     * @property $db Model
     * @property $userName string
     * @property $password string 32 bit hash
     * @return User will have a uid of 0 if failed to log in with given userName/password pair
     */
    public static function withUserNameAndPassword($userName, $password)
    {
        $db = Model::getInstance();
        $user_id = $db->get_var("SELECT id FROM Users WHERE username = '$userName' and password = '$password'");
        if($user_id)
        {
            try
            {
                $instance = User::withUid($user_id);
                $session = $instance->getFirstName() . $instance->getLastName() . $instance->getRegDate() . $instance->getUid() . $instance->getUserName() . time();
                $query = $db->query("INSERT INTO Sessions (session_key) VALUES ('" . md5($session) . "')");
                $session_id = $db->insert_id;
                $query = $db->query("INSERT INTO UserSessions (user_id, session_id) VALUES ('$instance->uid', '$session_id')");
                $_SESSION[USER_SESSION] = md5($session);
            }
            catch(UnknownUserException $ex)
            {
                Console::logError($ex, $ex->message);
            }
        }
        else
        {
            $instance = new User();
        }
        return $instance;
    }

    /**
     * Creates a new User based on the userName (this is not the logged in user)
     * Do not call function if the object is already created
     * @property $db Model
     * @property $username string
     */
    public static function withUserName($username)
    {
        $db = Model::getInstance();
        $user_id = $db->get_var("SELECT id FROM Users WHERE username = '$username'");
        if($user_id)
        {
            try
            {
                $instance = User::withUid($user_id);
            }
            catch(UnknownUserException $ex)
            {
                Console::logError($ex, $ex->message);
            }
        }
        else
        {
            $instance = new User();
        }
        return $instance;
    }

    /**
     * Creates a new User based on the uid (not a logged in user)
     * Do not call function if the object is already created
     * @property $db Model
     * @property $uid int
     */
    public static function withUid($uid)
    {
        $db = Model::getInstance();
        $user = $db->get_row("select * from Users where id = $uid");
        $instance = new User();
        if($user)
        {
            $instance->uid = $user["id"];
            $instance->userName = $user["username"];
            $instance->firstName = $user["first_name"];
            $instance->lastName = $user["last_name"];
            $instance->regDate = $user["date_created"];
        }
        if($instance->uid == 0)
        {
            throw new UnknownUserException(__FILE__, __LINE__, "UnknownUserException: user has not been created in db");
        }
        return $instance;
    }

    /************************
     * GETTERS AND SETTERS
     */

    /**
     *
     * @return String
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @property $what string
     * @throws UnknownUserException
     * @throws UpdateUserException
     * @return boolean
     */
    public function setFirstName($what)
    {
        $db = Model::getInstance();
        if($this->uid == ID_OF_UNKNOWN_USER)
        {
            throw new UnknownUserException(__FILE__, __LINE__, "UnknownUserException: user has not been created in db");
        }
        else
        {
            $result = $db->query("UPDATE " . USERS . " SET first_name = $what WHERE id = " . $this->getUid());
            if($result)
            {
                $this->firstName = $what;
                return true;
            }
            else
            {
                throw new UpdateUserException(__FILE__, __LINE__, "UpdateUserException: unable to update user with id '$this->uid'");
            }
        }
        return false;
    }

    /**
     * @property $what string
     * @throws UnknownUserException
     * @throws UpdateUserException
     * @return boolean
     */
    public function setLastName($what)
    {
        $db = Model::getInstance();
        if($this->uid == 0)
        {
            throw new UnknownUserException(__FILE__, __LINE__, "UnknownUserException: user has not been created in db");
        }
        else
        {
            $result = $db->query("UPDATE " . USERS . " SET last_name = $what WHERE id = " . $this->getUid());
            if($result)
            {
                $this->lastName = $what;
                return true;
            }
            else
            {
                throw new UpdateUserException(__FILE__, __LINE__, "UpdateUserException: unable to update user with id '$this->uid'");
            }
        }
        return false;
    }

    /**
     * The uid of the user. returns 0 if no logged in
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getRegDate()
    {
        return $this->regDate;
    }

    /************************
     * FUNCTIONS
     */

    /**
     * Gives a string formatted as follows: uid: '' userName: '' firstName: '' lastName: '' regDate: ''
     * @return string
     */
    public function toString()
    {
        return "uid: '$this->uid' userName: '$this->userName' firstName: '$this->firstName' lastName: '$this->lastName' regDate: '$this->regDate'";
    }
}
?>
