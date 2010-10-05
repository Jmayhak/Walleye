<?php

require('../includes/core/walleye.php');

// include libraries
require('../includes/core/libraries/RestRequest.inc.php');
$appOptions = Walleye_config::getAppOptions();
define('FPDF_FONTPATH', $appOptions['BASE'] . 'includes/core/libraries/font/');
require('../includes/core/libraries/fpdf.php');

$app = Walleye::getInstance();
$app->run();

?>
