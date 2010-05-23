<?php

require('../includes/config/config.php');
require('../includes/config/routes.php');
require('../includes/config/db.php');
require('../includes/core/walleye.php');

$app = Walleye::getInstance();
$app->setOptions = $wConfigOptions;
$app->setRoutes = $wRoutes;
$app->dbOptions = $wDbConfig;
$app->run();

?>
