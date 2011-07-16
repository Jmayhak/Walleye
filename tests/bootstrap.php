<?php

// include walleye
require realpath(dirname(__FILE__) . '/../includes/core/walleye.php');

// configure the app
$appOptions = array(
    // use \Walleye\Walleye::TESTING for unit tests
    // if in \Walleye\Walleye::PRODUCTION, no php warning/errors will be shown
    'ENVIRONMENT'				=>	\Walleye\Walleye::TESTING,
    // errors and logs are logged to the Logs table in the db
    'LOG_ERRORS'				=>	true,
    // Enter the expiration time in days
    'REG_KEY_EXPIRE_TIME'		=>	'7',
    // The length a session lasts in days (php.ini controls the session variable)
    'SESSION_KEY_EXPIRE_TIME'	=>	'1',
    // The name emails are masked as from
    'EMAIL_FROM'				=>	'',
    // Print out information about the application on GET and POST (WILL STOP REDIRECT)
    'PRINT_APP_INFO_ON_LOAD'	=>	false,
    // the user id to use for testing
    'TESTING_USER_ID'           =>  1
);

// configure the database
$dbOptions = array(
    // TESTING
    'TEST_ENGINE' => 'mysql',
    'TEST_SERVER' => '127.0.0.1',
    'TEST_USER' => '',
    'TEST_PASS' => '',
    'TEST_DATABASE' => '',
    'TEST_PORT' => '3306',
    'TEST_SALT' => ''
);

$walleye = Walleye\Walleye::getInstance($appOptions, $routes, $dbOptions);

// only run unit tests in the testing environment
if ($walleye->isTesting() == false) {
    exit('Please use the TESTING environment when performing unit tests' . "\n");
}

include_once realpath(dirname(__FILE__) . '/../') . '/includes/app/controllers/controller.php';
include_once realpath(dirname(__FILE__) . '/../') . '/includes/app/models/model.php';
include_once realpath(dirname(__FILE__) . '/../') . '/includes/app/models/user.php';

// include all app controllers
foreach (glob(dirname(__FILE__) . '/../includes/app/controllers/*.php') as $controller) {
    include_once $controller;
}

// include all app models
foreach (glob(dirname(__FILE__) . '/../includes/app/models/*.php') as $model) {
    include_once $model;
}

// run migrations
printf("Migrating test dbs...\n");
exec('php main.php db:migrate ENV=testing', $result);
foreach ($result as $line) {
    if ($line != '') {
        printf($line . "\n");
    }

}
printf("Finished\n");

/* End of file */
