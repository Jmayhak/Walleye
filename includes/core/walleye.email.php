<?php

/**
 * walleye.email.php
 *
 * This class handles email
 *
 * @author Jonathan Mayhak <Jmayhak@gmail.com>
 * @version 0.8
 * @package Walleye
 */
class Walleye_email {

    /**
     * @var string
     */
    public $to;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $message;

    /**
     * A new Walleye_email should have a to and a subject set on construction. The message can be sent as well
     * as the third parameter or you can choose to render an email view through the withEmailTemplate() static function
     *
     * @see Walleye_email::withEmailTemplate()
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return void
     */
    public function __construct($to, $subject, $message) {
        $this->to = $to;
        $this->from = 'no-reply'; // have this set in the config file
        $this->subject = $subject;
        $this->message = $message;
        // set additional default header info
    }

    /**
     * Use this constructor to create a new Walleye_email class with a given email template and values. The template should not contain
     * the <?php ?> tags and should use #{value} to define a value
     *
     * @static
     * @param string $to
     * @param string $subject
     * @param string $template the email template you want to use. ex. welcome.php will be loaded from the views/email/ dir
     * @param array $values the values to fill the email template with
     * @return Walleye_email
     */
    public static function withTemplate($to, $subject, $template, $values = array()) {
        foreach ($values as $tag => $value) {
            $template = preg_replace('/(#\{' . $tag . '\}?)/m', $value, file_get_contents(Walleye::getServerBaseDir() . 'views/email/' . $template));
        }
        $instance = new Walleye_email($to, $subject, $template);
        return $instance;
    }

    /**
     * Sends the email to recipient
     * @return boolean
     */
    public function send() {
        $return = mail($this->to, $this->subject, $this->message);
        return $return;
    }
}

?>
