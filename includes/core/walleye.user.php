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
     * Creates a new User based on the uid (not a logged in user)
     * Do not call function if the object is already created.
     *
     * @todo exception handling
     * @property string $id
     */
    public function __construct($id) {
        $db = new Walleye_database();
        $get_user_stmt = $db->prepare('select id, username, first_name, last_name, date_created from Users where id = ?');
        $get_user_stmt->bind_param('i', $id);
        $get_user_stmt->execute();
        $get_user_stmt->bind_result($id, $username, $first_name, $last_name, $date_created);
        if ($get_user_stmt->fetch()) {
            $this->id = $id;
            $this->username = $username;
            $this->firstName = $first_name;
            $this->lastName = $last_name;
            $this->regDate = $date_created;
        }
        else {
            $this->id = 0;
            $this->username = '';
            $this->firstName = '';
            $this->lastName = '';
            $this->regDate = '';
        }
    }

    /**
     * Creates the User model based on the session array. This IS the logged in user.
     * Use this constructor if the user is not doing something critical
     *
     * @return Walleye_user
     */
    public static function withSession() {
        // TODO check if session has expired before execute()
        // TODO check date_created on session
        $db = new Walleye_database();
        if (isset($_SESSION[Walleye_user::USER_SESSION])) {
            $get_session_id_stmt = $db->prepare('SELECT id FROM Sessions WHERE session_key = :session_key');
            $get_session_id_stmt->execute(array('sessions_key' => $_SESSION[Walleye_user::USER_SESSION]));
            $user_session_row = $get_session_id_stmt->fetch();
            $session_id = $user_session_row['id'];
            $get_user_id_and_date_created_stmt = $db->prepare('SELECT user_id, date_created FROM UserSessions WHERE session_id = :session_id');
            $get_user_id_and_date_created_stmt->execute(array('session_id' => $session_id));
            $user_session = $get_user_id_and_date_created_stmt->fetch();
        }
        if (isset($user_session['user_id'])) {
            try
            {
                $instance = Walleye_user::withId($user_session['user_id']);
            }
            catch (Exception $ex)
            {
                Console::logError($ex, $ex->message);
                $instance = null;
            }
        }
        else
        {
            $instance = null;
        }
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
    public static function withUserNameAndPassword($username, $password) {
        $db = new Walleye_database();
        $get_user_id_stmt = $db->prepare('SELECT id FROM Users WHERE username = :username and password = :password');
        $get_user_id_stmt->execute(array(':username' => $username, ':password' => $password));
        while ($user = $get_user_id_stmt->fetch()) {
        if (isset($user['id'])) {
            try
            {
                // !todo add whirlpool hash instead of md5 for creation of session_key
                $instance = self::withId($user['id']);
                $session = md5($instance->firstname . $instance->lastname . $instance->regDate . $instance->id . $instance->username . time());
                $insert_session_stmt = $db->prepare('INSERT INTO Sessions (session_key) VALUES (:session))');
                $insert_session_stmt->execute(array(':session' => $session));
                $session_id = $db->lastInsertId();
                $insert_user_session_stmt = $db->prepare('INSERT INTO UserSessions (user_id, session_id) VALUES (:id, :session_id)');
                $insert_user_session_stmt->execute(array(':id' => $instance->id, ':session_id' => $session_id));
                $_SESSION[Walleye_user::USER_SESSION] = md5($session);
            }
            catch (Exception $ex)
            {
                Console::logError($ex, $ex->message);
                $instance = null;
            }
        }
        else
        {
            $instance = null;
        }
        }
        return $instance;
    }

    /**
     * Creates a new User based on the username.
     *
     * @todo exception handling
     * @property string $username
     */
    public static function withUserName($username) {
        $db = new Walleye_database();
        $get_user_id_stmt = $db->prepare('SELECT id FROM Users WHERE username = :username');
        $get_user_id_stmt->execute(array(':username' => $username));
        $user = $get_user_id_stmt->fetch();
        if ($user['id']) {
            try
            {
                $instance = self::withId($user['id']);
            }
            catch (Exception $ex)
            {
                Console::logError($ex, $ex->message);
                $instance = null;
            }
        }
        else
        {
            $instance = null;
        }
        return $instance;
    }
    
    /**
     * Accepts the id of the user in the db
     *
     * @return Walleye_user|null
     */
    public static function withId($id) {
        $instance = new Walleye_user($id);
        if ($instance->id == 0) {
            return null;
        }
        return $instance;
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
        $db = new Walleye_database();
        if (Walleye_user::isUsernameUnique($username)) {
            $insert_user_stmt = $db->prepare('INSERT INTO Users (username, password) VALUES (:username, :password)');
            $insert_user_stmt->execute(array(':username' => $username, ':password' => $password));
            $user_id = $db->lastInsertId();
            $instance = new Walleye_user();
            $instance->id = $user_id;
            return $instance;
        }
        return null;
    }

    /**
     * Call this function after you have updated properties in the object. This function
     * will NOT change a users username, id, registration date or password.
     *
     * @return boolean
     */
    public function commit() {
        $db = new Walleye_database();
        $update_user_stmt = $db->prepare('UPDATE Users SET (firstName = :firstName, lastName = :lastName) WHERE (id = :id)');
        $result = $update_user_stmt->execute(array(':firstName' => $this->firstName, ':lastName' => $this->lastName, ':id' => $this->id));
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
        $db = new Walleye_database();
        $get_username_stmt = $db->prepare('SELECT id FROM Users WHERE username = :username');
        $result = $get_username_stmt->execute(array(':username' => $username));
        if (empty($result)) {
            return true;
        }
        return false;
    }
    
    /**
     * @return string|int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gives a string formatted as follows: id: '' userName: '' firstName: '' lastName: '' regDate: ''
     * @return string
     */
    public function __toString() {
        return "id: '$this->id' userName: '$this->username' firstName: '$this->firstName' lastName: '$this->lastName' regDate: '$this->regDate'";
    }
}

?>
