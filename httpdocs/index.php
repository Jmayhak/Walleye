<?php

// Be sure to configure the app in walleye.config.php

require('../includes/core/walleye.php');

$appOptions = \Walleye\Config::getAppOptions();

// include libraries here
require('../includes/core/libraries/RestRequest.inc.php');
define('FPDF_FONTPATH', $appOptions['BASE'] . 'includes/core/libraries/font/');
require('../includes/core/libraries/fpdf.php');

// PRODUCTION
if ($appOptions['PRODUCTION']) {
    // Turn off all error reporting
    ini_set('display_errors', 0);
}
    // DEVELOPMENT
else {
    ini_set('display_errors', 1);
}

// run the application
$app = \Walleye\Walleye::getInstance();
$app->run();
