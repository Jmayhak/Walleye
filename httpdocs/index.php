<?php

define('EXECUTION_TIME_START', microtime());

// show errors when app is initializing
ini_set('display_errors', 1);

if (floatval(phpversion()) < 5.3) { exit('PHP 5.3 or greater is required for Walleye to function.' . "\n"); }

// Be sure to configure the app in walleye.config.php
require('../includes/core/walleye.php');

$appOptions = \Walleye\Config::getAppOptions();

// perform checks on config file to make sure app can run
if ($appOptions['BASE'] == '') { exit('Please define the BASE directory' . "\n"); }
if ($appOptions['ENVIRONMENT'] == \Walleye\Config::TESTING) { exit('Change the Environment from TESTING. TESTING should only be used for unit tests.' . "\n"); }
if ($appOptions['ENVIRONMENT'] == \Walleye\Config::DEVELOPMENT && $appOptions['DEV_DOMAIN'] == '') { exit('Please define the DEV_DOMAIN.' . "\n"); }
if ($appOptions['ENVIRONMENT'] == \Walleye\Config::PRODUCTION && $appOptions['PROD_DOMAIN'] == '') { exit('Please define the PROD_DOMAIN.' . "\n"); }
if ( ! is_bool($appOptions['LOG_ERRORS'])) { exit('LOG_ERRORS must be a boolean.' . "\n"); }
if ( ! is_numeric($appOptions['SESSION_KEY_EXPIRE_TIME'])) { exit('SESSION_KEY_EXPIRE_TIME should be set AND be numeric' . "\n"); }
if ( ! is_bool($appOptions['PRINT_APP_INFO_ON_LOAD'])) { exit('PRINT_APP_INFO_ON_LOAD must be a boolean.' . "\n"); }

if ($appOptions['ENVIRONMENT'] == \Walleye\Config::PRODUCTION) {
    // Turn off all error reporting
    ini_set('display_errors', 0);
}

// run the application
$app = \Walleye\Walleye::getInstance();
$app->run();
