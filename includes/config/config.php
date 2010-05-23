<?php

$wConfigOptions = array(
    'BASE' => '',
    'PRODUCTION' => true,
    'LOCAL' => false,
    //Define the models found in includes/app/models/
    'MODELS' => array(
        'USER' => 'user.php'
    ),
    //Define the controllers found in includes/app/controllers/
    'CONTROLLERS' => array(
        'USER' => 'user.php',
        'API' => 'api.php'
    ),
    //Define the views found in includes/app/views/
    'VIEWS' => array(
        'BASE_HEADER_VIEW' => '_header.php',
        'BASE_FOOTER_VIEW' => '_footer.php',
        'BASE_INDEX_VIEW' => 'index.php',
        'LOGIN_VIEW' => 'user/login.php',
        'MOBILE_VIEW' => 'mobile/mobile.php'
    ),
    //Define the static files found in httpdocs/
    'STATIC' => array(
        'DEFAULT_STYLESHEET' => '/css/default.css',
        'PQP_OVERLAY' => '/plugins/pqp/overlay.gif',
        'PQP_CSS' => '/plugins/pqp/pqp.css',
        'PQP_SIDE' => '/plugins/pqp/side.png'
    )
);

?>
