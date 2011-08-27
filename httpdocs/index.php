<?php

define('EXECUTION_TIME_START', microtime());

// show errors when app is initializing
ini_set('display_errors', 1);

if (floatval(phpversion()) < 5.3) { exit('PHP 5.3 or greater is required for Walleye to function.' . "\n"); }

require(realpath(dirname(__FILE__) . '/../') . '/includes/core/walleye.php');

// get app options
require(realpath(dirname(__FILE__) . '/../') . '/includes/app/app.php');

// get db credentials
require(realpath(dirname(__FILE__) . '/../') . '/includes/app/db.php');

// get routes
require(realpath(dirname(__FILE__) . '/../') . '/includes/app/routes.php');

// perform checks to make sure app can run
if ($appOptions['ENVIRONMENT'] == \Walleye\Walleye::TESTING) { exit('Change the Environment from TESTING. TESTING should only be used for unit tests.' . "\n"); }
if ($appOptions['ENVIRONMENT'] != \Walleye\Walleye::DEVELOPMENT) { if ($appOptions['ENVIRONMENT'] != \Walleye\Walleye::PRODUCTION) { exit('Please define the ENVIRONMENT with either \Walleye\Walleye::PRODUCTION or \Walleye\Walleye::DEVELOPMENT.'); } }
if ( ! is_bool($appOptions['LOG_ERRORS'])) { exit('LOG_ERRORS must be a boolean.' . "\n"); }
if ( ! is_numeric($appOptions['SESSION_KEY_EXPIRE_TIME'])) { exit('SESSION_KEY_EXPIRE_TIME should be set AND be numeric' . "\n"); }
if ( ! is_bool($appOptions['PRINT_APP_INFO_ON_LOAD'])) { exit('PRINT_APP_INFO_ON_LOAD must be a boolean.' . "\n"); }
if (array_key_exists('default', $routes) === FALSE) { exit('Please define a default route.'); }

if ($appOptions['ENVIRONMENT'] == \Walleye\Walleye::PRODUCTION) {
    // Turn off all error reporting
    ini_set('display_errors', 0);
}

// run the application
$app = \Walleye\Walleye::getInstance($appOptions, $routes, $dbOptions);
$app->run();

/* End of file */
