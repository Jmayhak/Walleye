<?php

// include walleye
require realpath(dirname(__FILE__) . '/../includes/core/walleye.php');

// only run unit tests in the testing environment
$appOptions = Walleye\Config::getAppOptions();
if ($appOptions['ENVIRONMENT'] != Walleye\Config::TESTING) {
    exit('Please use the TESTING environment when performing unit tests' . "\n");
}

// include all app controllers
foreach (glob(dirname(__FILE__) . '/../includes/app/controllers/*.php') as $controller) {
    include $controller;
}

// include all app models
foreach (glob(dirname(__FILE__) . '/../includes/app/models/*.php') as $model) {
    include $model;
}

// reset walleye specifics
$sql = file_get_contents(dirname(__FILE__) . '/walleye.test.sql');
$db = new \Walleye\Database();
$reset_stmt = $db->multi_query($sql);
