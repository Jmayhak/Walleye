<?php

namespace Walleye;

/**
 * walleye.user.php
 *
 * The basic Walleye user class. This class provides functionality such as session handling, user creation,
 * user logging, and user updating.
 *
 * The user model that you create for your application should extend this class.
 *
 * @author	Jonathan Mayhak <Jmayhak@gmail.com>
 * @package	Walleye
 */
class User implements Model
{

    /**
     * The sessions key that is used to retrieve the session_key field in the Sessions table
     */
    const USER_SESSION = 'user';

    /**
     * The id of a user that was not found in the database
     */
    const ID_OF_UNKNOWN_USER = null;

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
     * The registration date of the user in Users table
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
     * @var User
     * @access private
     */
    private static $current_logged_user;

    /**
     * Creates a new User based on the uid (not a logged in user).
     *
     * DO NOT call this constructor directly.
     *
     * @property string $id
     */
    public function __construct($id)
    {
        $db = new Database();
        $get_user_stmt = $db->prepare('SELECT id, username, first_name, last_name, date_created FROM Users WHERE id = ?');
        $get_user_stmt->bind_param('i', $id);
        $get_user_stmt->execute();
        if ($row = $db->getRow($get_user_stmt)) {
            $this->id = $row->id;
            $this->username = $row->username;
            $this->firstName = $row->first_name;
            $this->lastName = $row->last_name;
            $this->regDate = $row->date_created;
        }
        else {
            $this->id = User::ID_OF_UNKNOWN_USER;
        }
    }

    /**
     * Creates a new user based on the id of the user in the Users table
     *
     * @static
     * @return User
     */
    public static function withId($id)
    {
        $instance = new User($id);
        if ($instance->getId() == User::ID_OF_UNKNOWN_USER) {
            $instance = null;
        }
        return $instance;
    }

    /**
     * Creates the User model based on the session array. This IS the logged in user.
     * Use this constructor if the user is not doing something critical
     *
     * @static
     * @return User
     */
    public static function withSession()
    {
        $instance = null;
        if (isset($_SESSION[User::USER_SESSION])) {
            $db = new Database();

            // get the session in the db
            $get_session_id_stmt = $db->prepare('SELECT id FROM Sessions WHERE session_key = ?');
            $get_session_id_stmt->bind_param('s', $_SESSION[User::USER_SESSION]);
            $get_session_id_stmt->execute();
            $session_id = ($session_row = $db->getRow($get_session_id_stmt)) ? $session_row->id : null;
            $get_session_id_stmt->close();

            // find the user this session is associated with
            $get_user_id_and_date_created_stmt = $db->prepare('SELECT user_id, date_created FROM UserSessions WHERE session_id = ?');
            $get_user_id_and_date_created_stmt->bind_param('i', $session_id);
            $get_user_id_and_date_created_stmt->execute();
            if ($session_id && $usersession_row = $db->getRow($get_user_id_and_date_created_stmt)) {
                $user_id = $usersession_row->user_id;
                $date_created = $usersession_row->date_created;

                // make sure this session hasn't expired in the database
                $date_created_array = explode(' ', $date_created);
                $appOptions = \Walleye\Walleye::getInstance()->getAppOptions();
                if (daysFromNow($date_created_array[0]) <= $appOptions['SESSION_KEY_EXPIRE_TIME']) {
                    $instance = User::withId($user_id);
                }
            }
            $get_user_id_and_date_created_stmt->close();
        }
        return $instance;
    }

    /**
     * Creates a new User based on the username and password pair. This IS the logged in user
     * Use this constructor to log in the user and if the user is doing something critical.
     *
     * A new session is started.
     *
     * This is where the session is created if the username/password are correct.
     *
     * The password should be passed already hashed
     *
     * @static
     * @param string $username
     * @param string $password
     * @return User
     */
    public static function withUserNameAndPassword($username, $password)
    {
        $instance = null;

        $db = new Database();
        $get_user_id_stmt = $db->prepare('SELECT id FROM Users WHERE username = ? AND password = ?');
        $get_user_id_stmt->bind_param('ss', $username, $password);
        $get_user_id_stmt->execute();

        if ($row = $db->getRow($get_user_id_stmt)) {
            $get_user_id_stmt->close();

            // create the new user object
            if (!is_null($user = User::withId($row->id))) {

                // set the session for this user
                $session = User::getSessionKey($user);
                $insert_session_stmt = $db->prepare('INSERT INTO Sessions (session_key) VALUES (?)');
                $insert_session_stmt->bind_param('s', $session);
                if ($insert_session_stmt->execute()) {
                    $session_id = $db->insert_id;
                    $insert_user_session_stmt = $db->prepare('INSERT INTO UserSessions (user_id, session_id) VALUES (?, ?)');
                    $insert_user_session_stmt->bind_param('ii', $user->getId(), $session_id);
                    if ($insert_user_session_stmt->execute()) {
                        $_SESSION[User::USER_SESSION] = $session;
                        self::$current_logged_user = $instance = User::withSession();
                    }
                    $insert_user_session_stmt->close();
                }
                $insert_session_stmt->close();
            }
        }
        return $instance;
    }

    /**
     * Creates a new User based on the username.
     *
     * @static
     * @param string $userName
     * @return User
     */
    public static function withUserName($userName)
    {
        $instance = null;

        if (is_string($userName)) {
            $db = new Database();
            $get_user_id_stmt = $db->prepare('SELECT id FROM Users WHERE username = ?');
            $get_user_id_stmt->bind_param('s', $userName);
            $get_user_id_stmt->execute();
            if ($user_id = $db->getRow($get_user_id_stmt)) {
                $instance = User::withId($user_id);
            }
        }
        return $instance;
    }

    /**
     * Use this function to create a new user in the database. It will return null if the username is not unique.
     *
     * @static
     * @param string $username
     * @param string $password send in hashed form. *DO NOT SEND CLEAR TEXT*
     * @return User
     */
    public static function create($username = null, $password = null)
    {
        $instance = null;

        if ($username && $password) {
            $db = new Database();

            if (User::isUsernameUnique($username)) {
                $insert_user_stmt = $db->prepare('INSERT INTO Users (username, password) VALUES (?, ?)');
                $insert_user_stmt->bind_param('ss', $username, $password);
                if ($insert_user_stmt->execute()) {
                    $user_id = $db->insert_id;
                    $instance = User::withId($user_id);
                }
            }
        }
        return $instance;
    }

    /**
     * Call this function after you have updated properties in the object. This function
     * will NOT change a users username, id, registration date or password.
     *
     * @return boolean
     */
    public function commit()
    {
        $db = new Database();
        $update_user_stmt = $db->prepare('UPDATE Users SET first_name = ?, last_name = ? WHERE id = ?');
        $update_user_stmt->bind_param('ssi', $this->firstName, $this->lastName, $this->id);
        return $update_user_stmt->execute();
    }

    /**
     * Returns the currently logged in user via sessions.
     *
     * @static
     * @see User::withSession()
     * @return User
     */
    public static function getLoggedUser()
    {
        if (!self::$current_logged_user) {
            if (Walleye::getInstance()->isTesting()) {
                self::$current_logged_user = User::withId(Walleye::getInstance()->getTestingUserId());
            }
            else {
                self::$current_logged_user = User::withSession();
            }
        }
        return self::$current_logged_user;
    }

    /**
     * Checks if the passed string is unique in the Users table
     *
     * @static
     * @param string $username
     * @return boolean
     */
    public static function isUsernameUnique($username)
    {
        $db = new Database();
        $get_username_stmt = $db->prepare('SELECT id FROM Users WHERE username = ?');
        $get_username_stmt->bind_param('s', $username);
        $get_username_stmt->execute();
        if ($row = $db->getRow($get_username_stmt)) {
            return true;
        }
        return false;
    }

    /**
     * Change the password of a user. Assumes you have already checked if the user attempting to change
     * this user's password can peform this operation
     *
     * The password MUST be sent hashed. DO NOT send the password cleartext to this function.
     *
     * @param string $password
     * @return boolean
     */
    public function changePassword($password)
    {
        $db = new Database();
        $change_password_stmt = $db->prepare('UPDATE Users SET password = ? WHERE id = ?');
        $change_password_stmt->bind_param('si', $password, $this->getId());
        return $change_password_stmt->execute();
    }

    /**
     * @return string|int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Creates and returns a key to be used to mark this users session in the database and in the $_SESSION array
     * @param User $user
     * @return string
     */
    protected static function getSessionKey($user)
    {
        return hash_data($user->username . $user->regDate . time());
    }

    /**
     * Gives a string formatted as follows: id: '' userName: '' firstName: '' lastName: '' regDate: ''
     * @return string
     */
    public function __toString()
    {
        return "id: '$this->id' userName: '$this->username' firstName: '$this->firstName' lastName: '$this->lastName' regDate: '$this->regDate'";
    }
}

/* End of file */
