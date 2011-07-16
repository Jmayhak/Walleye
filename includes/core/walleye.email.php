<?php

namespace Walleye;

/**
 * walleye.email.php
 *
 * This class handles email. Extend this class to include header information
 *
 * @author	Jonathan Mayhak <Jmayhak@gmail.com>
 * @package	Walleye
 */
class Email
{

    /**
     * @var string
     */
    protected $to;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $headers;

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
    public function __construct($to, $subject, $message)
    {
        $this->to = $to;
        $appOptions = Walleye::getInstance()->getAppOptions();
        $this->from = (isset($appOptions['REPLY-TO_EMAIL'])) ? $appOptions['REPLY-TO_EMAIL'] : '';
        $this->subject = $subject;
        $this->message = $message;
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
    public static function withTemplate($to, $subject, $template, $values = array())
    {
        if (empty($values)) {
            $template = file_get_contents(Walleye::getInstance()->getServerBaseDir() . 'includes/app/views/email/' . $template);
        }
        else {
            foreach ($values as $tag => $value) {
                $template = preg_replace('/(#\{' . $tag . '\}?)/m', $value, file_get_contents(Walleye::getInstance()->getServerBaseDir() . 'includes/app/views/email/' . $template));
            }
        }
        $instance = new \Walleye\Email($to, $subject, $template);
        return $instance;
    }

    /**
     * Sends the email to recipient
     * @return boolean
     */
    public function send()
    {
        if ($this->headers == '') {
            $return = mail($this->to, $this->subject, $this->message);
        }
        else {
            $return = mail($this->to, $this->subject, $this->message, $this->headers);
        }
        return $return;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}

/* End of file */
