<?php

// Be sure to configure the app in walleye.config.php
require('../includes/core/walleye.php');

$appOptions = \Walleye\Config::getAppOptions();

if ($appOptions['PRODUCTION']) {
    // Turn off all error reporting
    ini_set('display_errors', 0);
}
else {
	// TESTING or DEVELOPMENT
    ini_set('display_errors', 1);
}

// run the application
$app = \Walleye\Walleye::getInstance();
$app->run();
