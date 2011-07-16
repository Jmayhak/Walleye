<?php

namespace App\Controllers;

/**
 * site.php
 *
 * This controller handlers all basic/static requests like the contact page or the index page.
 */
class Site extends \Walleye\Controller {

    /**
     * @see Walleye_controller
     * @param array $url
     * @param array $data
     */
    public function __construct($url, $data) {
        $this->url = $url;
        $this->data = $data;
        $this->handlers = array(
            '/^(\/contact)$/' => 'contactHandler',
            '/^(\/)$/' => 'indexHandler',
            'default' => 'error_404'
        );
    }

    protected function indexHandler() {
        \Walleye\Console::log('You can create log messages', 'site.php', __LINE__);
        \Walleye\Console::logError('You can create error messages');
        if (\App\Models\User::getLoggedUser()) {
            \Walleye\Console::alert('Cool, you logged in');
        }
        else {
            \Walleye\Console::alert('This is just to show that you can send alerts to the user server-side. Check out the javascript console');
        }
        $this->view('index.php');
    }

    protected function contactHandler() {
        $this->view('contact.php');
    }

    protected function error_404($view = '404.php', $values = array()) {
        \Walleye\Console::alert('How did you get here?');
        parent::error_404();
    }
}

/* End of file */
