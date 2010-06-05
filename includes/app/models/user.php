<?php

/**
 * The basic User class. This class defines what a user looks like and interacts with the db to set/get data. This class
 * should extended for other types of users that are in the roles table.
 *
 * @todo write a method to deal with creation of a new user
 */
class mUser extends wModel {

    const ID_OF_UNKNOWN_USER = 0;
    const USER_SESSION = 'user';

    private $id;
    private $userName;
    private $regDate;
    private $firstName;
    private $lastName;

    private static $current_logged_user;

    /**
     * The base User constructor. Sets everything to nothing.
     */
    function mUser() {
        $this->id = 0;
        $this->fips = 0;
        $this->userName = "";
        $this->regDate = "";
        $this->firstName = "";
        $this->lastName = "";
    }

    /**
     * Creates the User model based on the session array. This IS the logged in user.
     * Use this constructor if the user is not doing something critical
     *
     * If this function creates a mUser with an id of 0 do not forget to unset()
     * the object.
     *
     * @todo check date_created on session
     * @todo check if session has expired before execute()
     * @return User
     */
    public static function withSession() {
        $session_id = 0;
        $user_session = 0;
        $db = wDb::getInstance();
        if (isset($_SESSION[USER_SESSION])) {
            $get_session_id_stmt = $db->prepare('SELECT id FROM Sessions WHERE session_key = :session_key');
            $get_session_id_stmt->execute(array('sessions_key' => $_SESSION[mUser::USER_SESSION]));
            $user_session_row = $get_session_id_stmt->fetch();
            $session_id = $user_session_row['id'];
            $get_user_id_and_date_created_stmt = $db->prepare('SELECT user_id, date_created FROM UserSessions WHERE session_id = :session_id');
            $get_user_id_and_date_created_stmt->execute(array('session_id' => $session_id));
            $user_session = $get_user_id_and_date_created_stmt->fetch();
        }
        if (isset($user_session['user_id'])) {
            try
            {
                $instance = mUser::withId($user_session['user_id']);
            }
            catch (Exception $ex)
            {
                Console::logError($ex, $ex->message);
            }
        }
        else
        {
            $instance = new self();
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
     * If the returned User has an id of 0 do not forget to unset() the object.
     *
     * @todo add whirlpool hash instead of md5 for creation of session_key
     * @todo exception handling
     * @param string $username
     * @param string $password
     * @return User will have a uid of 0 if failed to log in with given userName/password pair
     */
    public static function withUserNameAndPassword($username, $password) {
        $db = wDb::getInstance();
        $get_user_id_stmt = $db->prepare('SELECT id FROM Users WHERE username = :username and password = :password');
        $get_user_id_stmt->execute(array(':username' => $username, ':password' => $password));
        $user = $get_user_id_stmt->fetch();
        if ($user['id']) {
            try
            {
                $instance = self::withId($user['id']);
                $session = md5($instance->getFirstName() . $instance->getLastName() . $instance->getRegDate() . $instance->getUid() . $instance->getUserName() . time());
                $insert_session_stmt = $db->prepare('INSERT INTO Sessions (session_key) VALUES (:session))');
                $insert_session_stmt->execute(array(':session' => $session));
                $session_id = $db->lastInsertId();
                $insert_user_session_stmt = $db->prepare('INSERT INTO UserSessions (user_id, session_id) VALUES (:id, :session_id)');
                $insert_user_session_stmt->execute(array(':id' => $instance->id, ':session_id' => $session_id));
                $_SESSION[mUser::USER_SESSION] = md5($session);
            }
            catch (Exception $ex)
            {
                Console::logError($ex, $ex->message);
            }
        }
        else
        {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Creates a new User based on the username.
     *
     * If this function returns a User with an id of 0 be sure to
     * unset() the object.
     *
     * @todo exception handling
     * @property string $username
     */
    public static function withUserName($username) {
        $db = wDb::getInstance();
        $get_user_id_stmt = $db->prepare('SELECT id FROM Users WHERE username = :username');
        $get_user_id_stmt->execute(array(':username' => $username));
        $user = $get_user_id_stmt->fetch();
        if ($user['id']) {
            try
            {
                $instance = self::withId($user_id);
            }
            catch (Exception $ex)
            {
                Console::logError($ex, $ex->message);
            }
        }
        else
        {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Creates a new User based on the uid (not a logged in user)
     * Do not call function if the object is already created.
     *
     * If this function returns a User with an id of 0 be sure to
     * unset() the object.
     *
     * @todo exception handling
     * @property string $id
     */
    public static function withId($id) {
        $db = wDb::getInstance();
        $get_user_stmt = $db->prepare('select * from Users where id = :id');
        $get_user_stmt->execute(array(':id' => $id));
        $user = $get_user_stmt->fetch();
        $instance = new self();
        if ($user) {
            $instance->id = $user["id"];
            $instance->userName = $user["username"];
            $instance->firstName = $user["first_name"];
            $instance->lastName = $user["last_name"];
            $instance->regDate = $user["date_created"];
        }
        if ($instance->id == 0) {
            throw new Exception('user has not been created in db');
        }
        return $instance;
    }

    /**
     * Returns the currently logged in user via sessions. If a user is not
     * set then it sets the user to nothing with an id of 0
     *
     * @see mUser::withSession()
     * @return User
     */
    public static function getLoggedUser() {
        if (!self::$current_logged_user) {
            self::$current_logged_user = mUser::withSession();
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
     * @see mUser::withSession()
     * @see mUser::withUserNameAndPassword()
     */
    public static function setLoggedUserWithSession() {
        self::$current_logged_user = mUser::withSession();
    }

    /**
     *
     * @return string
     */
    public function getUserName() {
        return $this->userName;
    }

    /**
     * @todo
     * @param string $what
     * @return boolean
     */
    public function setFirstName($what) {
        return false;
    }

    /**
     * @todo
     * @param string $what
     * @return boolean
     */
    public function setLastName($what) {
        return false;
    }

    /**
     * The uid of the user. returns 0 if no logged in
     * @return string
     */
    public function getUid() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName() {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName() {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getRegDate() {
        return $this->regDate;
    }

    /**
     * @return string gives the generic url for logging in a User
     */
    public static function getLoginUrl() {
        return '/user/login';
    }

    /**
     * @return string gives the generic url for logging in a User
     */
    public static function getLogoutUrl() {
        return '/user/logout';
    }

    /**
     * Gives a string formatted as follows: uid: '' userName: '' firstName: '' lastName: '' regDate: ''
     * @return string
     */
    public function __toString() {
        return "uid: '$this->id' userName: '$this->userName' firstName: '$this->firstName' lastName: '$this->lastName' regDate: '$this->regDate'";
    }
}

?>
