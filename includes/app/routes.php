<?php

// configure the routes. routes follow this naming convention: array('regexp' => 'controller')
// don't forget namespaces for controller
$routes = array(
    '/^(\/user)/' => 'App\Controllers\User',
    '/^(\/api)/' => 'App\Controllers\Api',
    'default' => 'App\Controllers\Site'
);

/* End of file */
