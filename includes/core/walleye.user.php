<?php

/**
 * walleye.user.php
 *
 * The basic exception class for a Walleye_user
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
 * @package Walleye
 */
class Walleye_user_exception extends Walleye_exception {
    public function __construct() {
        parent::__construct();
    }
}

/**
 * walleye.user.php
 *
 * The basic Walleye user class. This class provides functionality such as session handling, user creation,
 * user logging, and user updating.
 *
 * The user model that you create for your application should extend this class.
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.5
 * @package Walleye
 */
class Walleye_user extends Walleye_model {

    /**
     * The sessions key that is used to retrieve the session_key field in the Sessions table
     */
    const USER_SESSION = 'user';

    /**
     * The id of the user in the Users table
     * @var int|string
     * @access private
     */
    private $id;

    /**
     * The username of the user in the Users table
     * @var string
     * @access private
     */
    private $username;

    /**
     * The registraction date of the user in Users table
     * @var string
     * @access private
     */
    private $regDate;

    /**
     * The first name of the user in the Users table
     * @var string
     */
    public $firstName;

    /**
     * The last name of the user in the Users table
     * @var string
     */
    public $lastName;

    /**
     * @static
     * @var Walleye_user
     * @access private
     */
    private static $current_logged_user;

    /**
     * The base User constructor. Sets everything to nothing.
     */
    public function __construct($id) {
        // !TODO check if session has expired before execute()
        // !TODO check date_created on session
        $db = Walleye::getInstance()->db();
        if (isset($_SESSION[Walleye_user::USER_SESSION])) {
            $get_session_id_stmt = $db->prepare('SELECT id FROM Sessions WHERE session_key = ?');
            $get_session_id_stmt->bind_param('s', $_SESSION[Walleye_user::USER_SESSION]);
            $get_session_id_stmt->execute();
            $get_session_id_stmt->bind_result($session_id);
            $get_session_id_stmt->fetch();
            $get_session_id_stmt->close();
            $get_user_id_and_date_created_stmt = $db->prepare('SELECT user_id, date_created FROM UserSessions WHERE session_id = ?');
            $get_user_id_and_date_created_stmt->bind_param('i', $session_id);
            $get_user_id_and_date_created_stmt->execute();
            $get_user_id_and_date_created_stmt->bind_result($user_id, $date_created);
            $get_user_id_and_date_created_stmt->fetch();
            $get_user_id_and_date_created_stmt->close();
            try {
                $instance = new Walleye_user($user_id);
            }
            catch (Exception $ex) {
                Console::logError($ex, $ex->message);
                $instance = null;
            }
        }
        else
        {
            $instance = null;
        }
        $db->close();
        return $instance;
    }

    /**
     * Creates the User model based on the session array. This IS the logged in user.
     * Use this constructor if the user is not doing something critical
     *
     * @return Walleye_user
     */
    public static function withSession() {
        // !TODO check if session has expired before execute()
        // !TODO check date_created on session
        $db = Walleye::getInstance()->db();
        if (isset($_SESSION[Walleye_user::USER_SESSION])) {
            $get_session_id_stmt = $db->prepare('SELECT id FROM Sessions WHERE session_key = ?');
            $get_session_id_stmt->bind_param('s', $_SESSION[Walleye_user::USER_SESSION]);
            $get_session_id_stmt->execute();
            $get_session_id_stmt->bind_result($session_id);
            $get_session_id_stmt->fetch();
            $get_session_id_stmt->close();
            $get_user_id_and_date_created_stmt = $db->prepare('SELECT user_id, date_created FROM UserSessions WHERE session_id = ?');
            $get_user_id_and_date_created_stmt->bind_param('i', $session_id);
            $get_user_id_and_date_created_stmt->execute();
            $get_user_id_and_date_created_stmt->bind_result($user_id, $date_created);
            $get_user_id_and_date_created_stmt->fetch();
            $get_user_id_and_date_created_stmt->close();
            try {
                $instance = new Walleye_user($user_id);
            }
            catch (Exception $ex) {
                Console::logError($ex, $ex->message);
                $instance = null;
            }
        }
        else
        {
            $instance = null;
        }
        $db->close();
        return $instance;
    }

    /**
     * Creates a new User based on the userName and password pair. This IS the logged in user
     * Use this constructor to log in the user and if the user is doing something critical.
     * A new session is started.
     *
     * This is where the session is created if the username/password are correct.
     *
     * @param string $username
     * @param string $password
     * @return Walleye_user
     */
    public static function withUsernameAndPassword($username, $password) {
        // TODO add whirlpool hash instead of md5 for creation of session_key
        // TODO exception handling
        $db = Walleye::getInstance()->db();
        $get_user_id_stmt = $db->prepare('SELECT id FROM Users WHERE username = ? and password = ?');
        $get_user_id_stmt->bind_param('ss', $username, $password);
        $get_user_id_stmt->execute();
        $get_user_id_stmt->bind_result($id);
        $get_user_id_stmt->fetch();
        $get_user_id_stmt->close();
        try {
            $instance = new Walleye_user($id);
            $session = md5($instance->firstName . $instance->lastName . time());
            $insert_session_stmt = $db->prepare('INSERT INTO Sessions (session_key) VALUES (?)');
            $insert_session_stmt->bind_param('s', $session);
            $insert_session_stmt->execute();
            $session_id = $db->insert_id;
            $insert_session_stmt->close();
            $insert_user_session_stmt = $db->prepare('INSERT INTO UserSessions (user_id, session_id) VALUES (?, ?)');
            $insert_user_session_stmt->bind_param('ii', $id, $session_id);
            $insert_user_session_stmt->execute();
            $insert_user_session_stmt->close();
            $_SESSION[Walleye_user::USER_SESSION] = $session;
        }
        catch (Exception $ex) {
            Console::logError($ex, $ex->message);
            $instance = null;
        }
        $db->close();
        return $instance;
    }

    /**
     * Creates a new User based on the username.
     *
     * @todo exception handling
     * @property string $username
     */
    public static function withUsername($username) {
        $db = Walleye::getInstance()->db();
        $get_user_id_stmt = $db->prepare('SELECT id FROM Users WHERE username = ?');
        $get_user_id_stmt->bind_param('i', $username);
        $get_user_id_stmt->execute();
        $get_user_id_stmt->bind_result($id);
        $get_user_id_stmt->fetch();
        $get_user_id_stmt->close();
        if ($id) {
            try {
                $instance = new Walleye_user($id);
            }
            catch (Exception $ex) {
                Console::logError($ex, $ex->message);
                $instance = null;
            }
        }
        else {
            $instance = null;
        }
        $db->close();
        return $instance;
    }

    /**
     * Creates a new User based on the uid (not a logged in user)
     * Do not call function if the object is already created.
     *
     * @property string|int $id
     */
    public static function withId($id) {
        return new Walleye_user($id);
    }

    /**
     * Use this function to create a new user in the database. This function assumes you've already checked
     * if the username is unique. It will return null if the username is not unique
     *
     * @static
     * @param string $username
     * @param string $password send in hashed form. *DO NOT SEND CLEAR TEXT*
     * @return Walleye_user|null
     */
    public static function create($username, $password) {
        $db = Walleye::getInstance()->db();
        if (Walleye_user::isUsernameUnique($username)) {
            $insert_user_stmt = $db->prepare('INSERT INTO Users (username, password) VALUES (?, ?)');
            $insert_user_stmt->bind_param('ss', $username, $password);
            $insert_user_stmt->execute();
            $insert_user_stmt->close();
            $user_id = $db->lastInsertId();
            $instance = new Walleye_user($user_id);
            $db->close();
            return $instance;
        }
        $db->close();
        return null;
    }

    /**
     * Call this function after you have updated properties in the object. This function
     * will NOT change a users username, id, registration date or password.
     *
     * @return boolean
     */
    public function commit() {
        $db = Walleye::getInstance()->db();
        $update_user_stmt = $db->prepare('UPDATE Users SET (firstName = ?, lastName = ?) WHERE (id = ?)');
        $update_user_stmt->bind_param('ssi', $this->firstName, $this->lastName, $this->id);
        $result = $update_user_stmt->execute();
        $update_user_stmt->close();
        $update_user_stmt->close();
        return $result;
        
    }

    /**
     * Returns the currently logged in user via sessions.
     *
     * @see Walleye_user::withSession()
     * @return Walleye_user|null
     */
    public static function getLoggedUser() {
        if (!self::$current_logged_user) {
            self::$current_logged_user = Walleye_user::withSession();
        }
        return self::$current_logged_user;
    }

    /**
     * Sets the logged in user via Sessions. Use this after changing the session
     * of a user after reauthentication to make sure on the next view render the
     * user will be allowed access.
     *
     * The Session is updated by User::withUserNameAndPassword()
     *
     * @see Walleye_user::withSession()
     * @see Walleye_user::withUserNameAndPassword()
     */
    public static function setLoggedUserWithSession() {
        self::$current_logged_user = Walleye_user::withSession();
    }

    /**
     * Checks if the passed string is unique in the Users table
     *
     * @static
     * @param string $username
     * @return boolean
     */
    public static function isUsernameUnique($username) {
        $db = Walleye::getInstance()->db();
        $get_username_stmt = $db->prepare('SELECT id FROM Users WHERE username = ?');
        $get_username_stmt->bind_param('s', $username);
        $get_username_stmt->execute();
        if ($get_username_stmt->fetch()) {
            $get_username_stmt->close();
            $db->close();
            return true;
        }
        $get_username_stmt->close();
        $db->close();
        return false;
    }
    
    /**
     * @return int|string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gives a string formatted as follows: uid: '' userName: '' firstName: '' lastName: '' regDate: ''
     * @return string
     */
    public function __toString() {
        return "uid: '$this->id' username: '$this->username' firstName: '$this->firstName' lastName: '$this->lastName' regDate: '$this->regDate'";
    }
}

?>
