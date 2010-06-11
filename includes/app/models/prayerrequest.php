<?php

class PrayerRequest extends Walleye_model {

    /**
     * The id of the object in the db
     * @access private
     */
    private $id;

    /**
     * The name of the person who has this prayer request object in the db
     * @access public
     */
    public $name;

    /**
     * The email of the person who has this prayer request object in the db
     * @access public
     */
    public $email;

    /**
     * The date the object was created in the db
     * @access private
     */
    private $date;

    /**
     * The actual prayer request or message stored in the db
     * @access public
     */
    public $message;

    /**
     * The prayer request needs to be approved by an admin before it is published
     * @access private
     */
    private $approved;

    /**
     * The amount of times someone has said they would pray for this prayer request
     * @access private
     */
    private $count;


    /**
     * @return void
     */
    public function __construct() {
        $this->id = 0;
        $this->count = 0;
        $this->name = 'annoymous';
        $this->email = '';
        $this->message = '';
        $this->approved = true;
    }

    /**
     * @static
     * @param int|string $id
     * @return PrayerRequest
     */
    public static function withId($id) {
        $db = new Walleye_database();
        $get_prayer_by_id_stmt = $db->query('SELECT * FROM PrayerRequests WHERE id = :id');
        $get_prayer_by_id_stmt->execute(array(':id' => $id));
        $prayer_request = $get_prayer_by_id_stmt->fetch();
        if (isset($prayer_request['id'])) {
            $instance = new PrayerRequest();
            $instance->id = $prayer_request['id'];
            $instance->name = $prayer_request['name'];
            $instance->count = $prayer_request['count'];
            $instance->date = $prayer_request['date'];
            $instance->email = $prayer_request['email'];
            $instance->message = $prayer_request['message'];
            $instance->approved = $prayer_request['approved'];
        }
        else {
            $instance = null;
        }
        return $instance;
    }

    /**
     * Commits all properties except for id, date, and approval status
     *
     * @return void
     */
    public function commit() {
        if ($this->id == 0) $this->id = $this->create();
        $db = new Walleye_database();
        $set_prayer_by_id = $db->query('UPDATE PrayerRequests SET name = :name, count = :count, email = :email, message = :message WHERE id = :id');
        $set_prayer_by_id->execute(array(
            ':id' => $this->id,
            ':name' => $this->name,
            ':count' => $this->count,
            ':email' => $this->email,
            ':message' => $this->message
        ));
    }

    /**
     * Creates this object in the database and returns the id of the insert row
     *
     * @return string|int
     */
    private function create() {
        $db = new Walleye_database();
        $create_prayer_request_stmt = $db->query('INSERT INTO PrayerRequests (name, count, email, message, approved) VALUES (:name, :count, :email, :message, :approved)');
        $create_prayer_request_stmt->execute(array(
            ':name' => $this->name,
            ':count' => $this->count,
            ':email' => $this->email,
            ':message' => $this->message,
            ':approved' => $this->approved
        ));
        return $db->lastInsertId();
    }

    /**
     * @return void
     */
    public function approve() {
        $db = new Walleye_database();
        $update_prayer_request_stmt = $db->query('UPDATE PrayerRequests SET approved = :approved');
        $update_prayer_request_stmt->execute(array(
           ':approved' => true 
        ));
    }

    /**
     * @return void
     */
    public function isApproved() {
        return $this->approved;
    }

    /**
     * @return string
     */
    public function __toString() {
        return "id: $this->id name: $this->name email: $this->email count: $this->count approved: $this->approved date: $this->date message: $this->message";
    }
}

?>