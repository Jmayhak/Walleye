<?php

namespace App\Controllers;

/**
 * user.php
 *
 * This class is used to handle routes related to user management.
 */
class User extends \Walleye\Controller {

    /**
     * @param array $url
     * @param array $data
     */
    public function __construct($url, $data) {
        $this->url = $url;
        $this->data = $data;
        $this->path = $this->getUrlPath($url);
        $this->handlers = array(
            '/^(\/user\/login)$/' => 'loginHandler',
            '/^(\/user\/logout)$/' => 'logoutHandler',
            'default' => 'error_404'
        );
    }

    /**
     * Logs out the currently logged user.
     * Sets the view to be the homepage
     */
    public function logoutHandler() {
        if (\App\Models\User::getLoggedUser()) {
            unset($_SESSION[\Walleye\User::USER_SESSION]);
        }
        $this->redirect();
    }

    /**
     * Logs in the user
     * Sets the view to the the return url if given, the homepage, or the login
     * view if authentication fails
     */
    public function loginHandler() {
        $values = array();
        $data = $this->data;
        if ($this->isPost() && isset($data['username']) && isset($data['password'])) {
            $user = \App\Models\User::withUsernameAndPassword($data['username'], hash_data($data['password']));
            if (!is_null($user)) {
                if (isset($data['return_url']) && $data['return_url'] != '') {
                    $this->redirect($data['return_url']);
                }
                else {
                    $this->redirect();
                }
            }
            else {
                $values['login_message'] = 'Wrong user name and password combination';
                $this->view('user/login.php', $values);
            }
        }
        else {
            $this->view('user/login.php');
        }
    }
}

/* End of file */
